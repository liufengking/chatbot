<?php


namespace Commune\Chatbot\App\Platform\SwooleConsole;


use Commune\Chatbot\App\Messages\Text;
use Commune\Chatbot\App\Platform\ConsoleConfig;
use Commune\Chatbot\Blueprint\Conversation\ConversationMessage;
use Commune\Chatbot\Blueprint\Conversation\MessageRequest;
use Commune\Chatbot\Blueprint\Message\Message;
use Commune\Chatbot\Blueprint\Message\VerbalMsg;
use Commune\Chatbot\Framework\Conversation\MessageRequestHelper;
use Commune\Chatbot\Framework\Impl\SimpleConsoleLogger;
use Commune\Support\Uuid\HasIdGenerator;
use Swoole\Server;

class SwooleUserMessageRequest implements MessageRequest, HasIdGenerator
{
    use MessageRequestHelper;

    /**
     * @var string|Message
     */
    protected $data;

    /**
     * @var Server
     */
    protected $server;

    /**
     * @var ConsoleConfig
     */
    protected $config;

    /**
     * @var int
     */
    protected $fd;

    /**
     * @var array
     */
    protected $clientInfo;

    /*----- cached ----0*/

    /**
     * @var Message
     */
    protected $message;

    /**
     * @var ConversationMessage[]
     */
    protected $buffers = [];

    /**
     * @var string
     */
    protected $userId;

    /**
     * SwooleUserMessageRequest constructor.
     * @param Server $server
     * @param int $fd
     * @param string|Message $data
     * @param ConsoleConfig|null $config
     */
    public function __construct(
        Server $server,
        int $fd,
        $data,
        ConsoleConfig $config = null
    )
    {
        $this->data = $data;
        $this->server = $server;
        $this->config = $config;
        $this->fd = $fd;
        $this->config = $config ?? new ConsoleConfig();
        $this->clientInfo = $server->getClientInfo($fd);
    }

    /**
     * @return int
     */
    public function getFd()
    {
        return $this->fd;
    }

    public function getInput()
    {
        return $this->data;
    }

    public function getPlatformId(): string
    {
        return SwooleConsoleServer::class;
    }

    protected function makeInputMessage($input): Message
    {
        return new Text(strval($input));
    }


    public function fetchUserId(): string
    {
        return $this->userId
            ?? $this->userId = md5($this->clientInfo['remote_ip']);
    }

    public function fetchUserName(): string
    {
        return $this->clientInfo['remote_ip'];
    }

    public function fetchUserData(): array
    {
        return [];
    }

    public function bufferMessage(ConversationMessage $message): void
    {
        $this->buffers[] = $message;
    }

    public function sendResponse(): void
    {
        while ($message = array_shift($this->buffers)) {
            $this->write($message->getMessage());
        }
        $this->buffers = [];
    }

    protected function write(Message $msg) : void
    {
        // 显示一下颜色.
        if ($msg instanceof VerbalMsg) {

            switch ($msg->getLevel()) {
                case VerbalMsg::DEBUG:
                    $style = 'debug';
                    break;
                case VerbalMsg::INFO:
                    $style = 'info';
                    break;
                case VerbalMsg::WARN:
                    $style = 'warning';
                    break;
                default:
                    $style = 'error';
            }

            $this->server->send(
                $this->fd,
                SimpleConsoleLogger::wrapMessage(
                    $style,
                    $msg->getText()
                )
                . PHP_EOL
            );
        } else {
            $this->server->send($this->fd, $msg->getText() . PHP_EOL);
        }
    }

    protected function onBindConversation() : void
    {
    }

    public function validate(): bool
    {
        return true;
    }

    public function getScene(): ? string
    {
        return null;
    }

    public function sendRejectResponse(): void
    {
        $this->server->send($this->fd, __METHOD__);
    }

    public function sendFailureResponse(): void
    {
        $this->server->send($this->fd, __METHOD__);
    }


}