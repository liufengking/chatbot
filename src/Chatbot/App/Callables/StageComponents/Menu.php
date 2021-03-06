<?php


namespace Commune\Chatbot\App\Callables\StageComponents;


use Commune\Chatbot\App\Callables\Actions\Redirector;
use Commune\Chatbot\Blueprint\Message\Message;
use Commune\Chatbot\Framework\Exceptions\ChatbotLogicException;
use Commune\Chatbot\OOHost\Context\Callables\StageComponent;
use Commune\Chatbot\OOHost\Context\Context;
use Commune\Chatbot\OOHost\Context\Stage;
use Commune\Chatbot\OOHost\Dialogue\Dialog;
use Commune\Chatbot\OOHost\Directing\Navigator;

/**
 * 一个菜单式的组件. 方便做常见的菜单导航.
 * 原版比较复杂, 越复杂越不利于用户使用, 于是做了简化.
 *
 * - 现在是纯数字索引的菜单选项.
 * - 选项如果是 context, 会 sleepTo 该 context, 返回当前stage时默认执行 repeat
 *
 * 简化的原因, 想通了两个事情:
 * - 用户本来需要简单功能, 功能越多, 反而上手越困难.
 * - Menu 组件做得复杂没有意义, 因为 stage 本身的 api 已经够用了, 反而比 menu 简单.
 *
 */
class Menu implements StageComponent
{

    /**
     * @var string
     */
    protected $question;

    /**
     * @var callable[]  string $suggestion => callable $caller
     */
    protected $menu;

    /**
     * @var int|string|null
     */
    protected $defaultChoice;

    /**
     * @var callable|null
     */
    protected $hearingComponent;

    /**
     * @var callable|null
     */
    protected $redirector;

    /**
     * @var array
     */
    protected $slots = [];

    /**
     * Menu constructor.
     * @param string $question
     * @param callable[] $menu   预定义的菜单. 结构是:
     *    [
     *      '字符串作为suggestion' => callable ,
     *      '字符串作为suggestion' => stageName , //推荐使用的方式
     *      'contextName',
     *    ]
     * @param int|null $default
     */
    public function __construct(
        string $question,
        array $menu,
        int $default = null
    )
    {
        $this->question = $question;
        $this->menu = $menu;
        $this->defaultChoice = $default;
    }

    /**
     * 添加一个 hearing 的组件.
     * @param callable $hearing
     * @return Menu
     */
    public function onHearing(callable $hearing) : Menu
    {
        $this->hearingComponent = $hearing;
        return $this;
    }

    /**
     * 如果菜单的选项是 context name
     * 这个方法可以注册独特的跳转逻辑 .
     * function (Context $context, Dialog $dialog, string $contextName ) {}
     *
     * @param callable $redirector
     * @return Menu
     */
    public function onRedirect(callable  $redirector) : Menu
    {
        $this->redirector = $redirector;
        return $this;
    }

    /**
     * 给选项一个默认值. 否则是 0 .
     *
     * @param int $choice
     * @return Menu
     */
    public function withDefault(int $choice) : Menu
    {
        $this->defaultChoice = $choice;
        return $this;
    }

    /**
     * 给菜单的对白加参数.
     *
     * @param array $slots
     * @return Menu
     */
    public function withSlots(array $slots) : Menu
    {
        $this->slots = $slots;
        return $this;
    }


    /*-------- 内部方法 --------*/

    public function __invoke(Stage $stage) : Navigator
    {
        return $stage->talk(
            [$this, 'askToChoose'],
            [$this, 'heardChoice']
       );
    }

    public function heardChoice(Context $self, Dialog $dialog, Message $message) : Navigator
    {

        $hearing = $dialog->hear($message);
        $repo = $dialog->session->contextRepo;

        $suggestions = $this->buildMenuSuggestions($dialog);
        $keys = array_keys($suggestions);
        foreach ($this->menu as $key => $value) {
            $i = array_shift($keys);

            // 第一种情况, 值是一个context name
            // 说明要 redirect 过去
            if (is_string($value) && $repo->hasDef($value)) {
                $hearing = $hearing->isChoice(
                    $i,
                    $this->redirect($value)
                );

            // 第二种情况, 值是当前 context 下另一个 stage
            // 直接 go stage
            } elseif (
                is_string($value)
                && method_exists(
                    $self,
                    $method = Context::STAGE_METHOD_PREFIX
                        . ucfirst($value)
                )
            ) {
                $hearing = $hearing->isChoice(
                    $i,
                    Redirector::goStage($value)
                );

            // 第三种情况, 是callable
            } elseif (is_callable($value)) {
                $hearing = $hearing->isChoice($i, $value);

            // 第四种情况, 配置的不对.
            } else {

                $error = is_scalar($value)
                    ? gettype($value). ' '. $value
                    : gettype($value);

                throw new ChatbotLogicException(
                    static::class
                    . ' menu should only be string message(key) to callable value, '
                    . ' or int key to context name,'
                    . 'or string message(key) to stage name, '
                    . $error . ' given'
                );
            }

        }

        // 仍然允许按自己的意愿定义.
        if (isset($this->hearingComponent)) {
            $hearing->component($this->hearingComponent);
        }

        $navigator = $hearing->end();
        return $navigator;
    }

    public function askToChoose(Dialog $dialog) : Navigator
    {
        $suggestions = $this->buildMenuSuggestions($dialog);

        $dialog->say($this->slots)
            ->askChoose(
                $this->question,
                $suggestions,
                // 默认第一个值就是默认值.
                 $this->defaultChoice
                    ?? array_keys($suggestions)[0]
            );

        return $dialog->wait();
    }

    protected function buildMenuSuggestions(Dialog $dialog) : array
    {
        $repo = $dialog->session->contextRepo;
        $i = 0;
        $suggestions = [];
        foreach ($this->menu as $key => $value) {
            $i ++;
            // 第一种情况, 键名是 suggestion
            if (is_string($key)) {
                $parts = explode('::', $key, 2);
                $count = count($parts);
                $index = $count > 1 ? trim($parts[0]) : $i;
                $value = $count > 1 ? trim($parts[1]) : $key;
                $suggestions[$index] = $value;

                // 第二种情况, 键名是整数, 则值是 contextName
            } elseif ($repo->hasDef($value)) {
                $suggestions[$i] = $repo->getDef($value)->getDesc();
            }
        }
        return $suggestions;
    }

    public function redirect(
        string $contextName
    ) : \Closure
    {

        return function(Context $context, Dialog $dialog) use ($contextName) : Navigator {

            if (isset($this->redirector)) {
                return call_user_func($this->redirector, $context, $dialog, $contextName);
            }

            $repo = $dialog->session->intentRepo;
            // 意图用特殊的方式来处理.
            $navigator = null;
            if ($repo->hasDef($contextName)) {
                $intent = $repo->getDef($contextName)->newContext();
                $navigator =  $intent->navigate($dialog);
            }

            // 默认的重定向是 sleepTo
            // 这会导致无法 cancel 掉整个流程.
            return $navigator ?? $dialog->redirect->sleepTo($contextName);
        };
    }

}