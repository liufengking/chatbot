<?php


namespace Commune\Chatbot\Framework\Messages\QA;


use Commune\Chatbot\Framework\Messages\AbsConvoMsg;
use Commune\Chatbot\Blueprint\Message\QA\Answer;
use Commune\Chatbot\Blueprint\Message\Message;

abstract class AbsAnswer extends AbsConvoMsg implements Answer
{

    /**
     * @var string|int|null
     */
    protected $choice;

    /**
     * @var Message
     */
    protected $origin;

    /**
     * AbsAnswer constructor.
     * @param Message $origin
     * @param null|string|int $choice
     */
    public function __construct(
        Message $origin,
        $choice = null
    )
    {
        $this->origin = $origin;
        $this->choice = $choice;
        parent::__construct();
    }


    public function __sleep(): array
    {
        return array_merge(parent::__sleep(), [
            'choice',
            'origin',
        ]);
    }

    /**
     * @param int|string $choice
     * @return bool
     */
    public function hasChoice($choice): bool
    {
        if ($choice == '0') {
            $choice = 0;
        }

        if (is_numeric($choice)) {
            return is_numeric($this->choice) && $this->choice == $choice;
        }

        return $choice == $this->choice;
    }

    public function getOriginMessage(): Message
    {
        return $this->origin;
    }

    public function isEmpty(): bool
    {
        return false;
    }

    /**
     * text 保持 origin 原样
     * @return string
     */
    public function getText(): string
    {
        return $this->getOriginMessage()->getText();
    }


    public function getChoice()
    {
        return $this->choice;
    }


}