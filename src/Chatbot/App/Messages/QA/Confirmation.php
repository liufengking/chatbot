<?php


namespace Commune\Chatbot\App\Messages\QA;

use Commune\Chatbot\App\Messages\Text;
use Commune\Chatbot\Blueprint\Message\QA\Confirmation as Contract;

class Confirmation extends VbAnswer implements Contract
{
    /**
     * @return bool
     */
    public function toResult()
    {
        return !($this->choice == 0) ;
    }

    public function isPositive(): bool
    {
        return $this->hasChoice(1);
    }

    public static function mock()
    {
        return new Confirmation(new Text('y'), true, 1);
    }


}