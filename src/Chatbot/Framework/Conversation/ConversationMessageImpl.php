<?php


namespace Commune\Chatbot\Framework\Conversation;


use Carbon\Carbon;
use Commune\Chatbot\Blueprint\Conversation\ConversationMessage;
use Commune\Chatbot\Blueprint\Message\Message;
use Commune\Support\Arr\ArrayAbleToJson;


/**
 * Class ConversationMessageImpl
 * @package Commune\Chatbot\Framework\Conversation
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 *
 * @property-read string $id
 * @property-read string $chatId
 * @property-read string|null $sessionId
 * @property-read string $userId
 * @property-read string $traceId
 * @property-read string $platformId
 * @property-read Carbon $createdAt
 * @property-read Message $message
 * @property-read string|null $replyToId
 */
class ConversationMessageImpl implements ConversationMessage
{
    use ArrayAbleToJson;

    /**
     * @var string
     */
    protected $messageId;

    /**
     * @var Message
     */
    protected $message;

    /**
     * @var string
     */
    protected $chatId;

    /**
     * @var string
     */
    protected $userId;

    /**
     * @var string
     */
    protected $chatbotName;

    /**
     * @var string
     */
    protected $platformId;

    /**
     * @var Carbon
     */
    protected $createdAt;

    /**
     * @var string | null
     */
    protected $replyToId;

    /**
     * @var string | null
     */
    protected $sessionId;

    /**
     * @var string|null
     */
    protected $traceId;

    public function __construct(
        string $messageId,
        Message $message,
        string $userId,
        string $chatbotName,
        string $platformId,
        string $chatId,
        string $replyToId = null,
        string $sessionId = null,
        string $traceId = null
    )
    {
        $this->messageId = $messageId;
        $this->message = $message;
        $this->userId = $userId;
        $this->chatbotName = $chatbotName;
        $this->chatId = $chatId;

        $this->replyToId = $replyToId;
        $this->sessionId  = $sessionId;
        $this->platformId = $platformId;

        $this->createdAt = $message->getCreatedAt();
        $this->traceId = $traceId;
    }


    public function getTraceId(): string
    {
        return $this->traceId;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->messageId;
    }

    /**
     * @return Message
     */
    public function getMessage(): Message
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getChatbotName(): string
    {
        return $this->chatbotName;
    }


    /**
     * @return string
     */
    public function getPlatformId(): string
    {
        return $this->platformId;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    public function getChatId(): string
    {
        return $this->chatId;
    }

    public function getSessionId(): ? string
    {
        return $this->sessionId;
    }


    public function getReplyToId(): ? string
    {
        return $this->replyToId;
    }

    public function toArray() : array
    {
        return [
            'messageId' => $this->messageId,
            'message' => $this->message,
            'replyTo' => $this->replyToId,
            'chatId' => $this->chatId,
            'platformId' => $this->platformId,
            'userId' => $this->userId,
            'chatbotName' => $this->chatbotName,
            'createAt' => $this->getCreatedAt()->toDateTimeString(),
            'traceId' => $this->getTraceId(),
        ];
    }

    public function __get($name)
    {
        return $this->{$name};
    }
}