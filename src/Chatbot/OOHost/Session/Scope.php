<?php


namespace Commune\Chatbot\OOHost\Session;

use Commune\Chatbot\Blueprint\Conversation\Conversation;
use Commune\Support\Arr\ArrayAbleToJson;
use Commune\Support\Arr\ArrayAndJsonAble;


/**
 * 描述当前 session 的维度.
 *
 *
 *
 * @property string $userId
 * @property string $chatbotName
 * @property string $chatId
 * @property string $platformId
 * @property string $conversationId
 * @property string $incomingMessageId
 * @property string $sessionId
 * @property string $date
 * @property string $year
 * @property string $month
 * @property string $day
 * @property string $week
 * @property string $weekDay
 * @property string $hour
 * @property string $minute
 *
 */
class Scope implements ArrayAndJsonAble
{
    const SESSION_ID = 'sessionId';
    const USER_ID = 'userId';
    const PLATFORM_ID = 'platformId';
    const CHATBOT_NAME = 'chatbotName';
    const CHAT_ID = 'chatId';
    const CONVERSATION_ID = 'conversationId';
    const INCOMING_MESSAGE_ID = 'incomingMessageId';
    const DATE = 'date';
    const YEAR = 'year';
    const MONTH = 'month';
    const DAY = 'day';
    const WEEK = 'week';
    const WEEK_DAY = 'weekDay';
    const HOUR = 'hour';
    const MINUTE = 'minute';

    use ArrayAbleToJson;

    /**
     * @var \ReflectionProperty[]
     */
    private static $properties;

    private $userId = '';

    private $chatbotName = '';

    private $platformId = '';

    private $chatId = '';

    private $conversationId = '';

    private $sessionId = '';

    private $incomingMessageId = '';

    private $year = '';

    private $month = '';

    private $week = '';

    private $weekDay = '';

    private $date = '';

    private $day = '';

    private $hour = '';

    private $minute = '';

    /**
     * Scope constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            }
        }
    }


    public static function make(string $sessionId, Conversation $conversation) : Scope
    {
        $scope = new self();
        $scope->sessionId = $sessionId;
        $message = $conversation->getIncomingMessage();
        $scope->userId = $message->getUserId();
        $scope->chatbotName = $message->getChatbotName();
        $scope->platformId = $message->getPlatformId();
        $scope->chatId = $message->getChatId();
        $scope->conversationId = $conversation->getConversationId();
        $scope->incomingMessageId = $message->getId();
        $carbon = $message->getCreatedAt();

        $scope->date = $carbon->toDateString();
        $scope->year = (string)$carbon->year;
        $scope->month = (string)$carbon->month;
        $scope->day = (string)$carbon->day;
        $scope->week = (string)$carbon->week;
        $scope->weekDay = (string)$carbon->dayOfWeek;
        $scope->hour = (string)$carbon->hour;
        $scope->minute = (string)$carbon->minute;
        return $scope;
    }

    /**
     * @return \ReflectionProperty[]
     * @throws
     */
    private static function getProperties() : array
    {
        if (isset(self::$properties)) {
            return self::$properties;
        }
        $r = new \ReflectionClass(self::class);
        self::$properties = array_filter($r->getProperties(), function(\ReflectionProperty $property){
            return !$property->isStatic();
        });

        return self::$properties;
    }

    public function __get($name)
    {
        return $this->{$name};
    }

    public function toArray() : array
    {
        $results = [];
        foreach ($this->getProperties() as $p) {
            $name = $p->getName();
            $results[$name] = $this->{$name};
        }
        return $results;
    }

    public function geScopeNames()
    {
        return array_map(function(\ReflectionProperty $p) {
            return $p->getName();
        }, self::getProperties());
    }

    /**
     * @param string $type  通常是 context name
     * @param array $scopes
     * @return string
     */
    public function makeScopingId(string $type, array $scopes) : string
    {

        if (empty($scopes)) {
            return '';
        }

        // 用chatId 隔离其他用户.
        // $hash = "$type:{$this->chatId}";


        // 不再隔离用户. 要增加一个默认的 lock 方法.
        $hash = "$type:";
        foreach ($scopes as $scope) {
            $hash.= ':' . $scope . ':' . $this->{$scope};
        }

        return sha1($hash);
    }
}