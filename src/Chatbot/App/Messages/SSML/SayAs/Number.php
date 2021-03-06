<?php


namespace Commune\Chatbot\App\Messages\SSML\SayAs;


use Commune\Chatbot\Framework\Messages\AbsSSML;

class Number extends AbsSSML
{
    const ORDINAL = 'ordinal';

    const DIGITS = 'digits';

    const SCORE = 'score';

    const FRACTION = ' fraction';

    public function __construct($num, string $type = '')
    {
        $realType = empty($type) ? 'number' : "number:$type";
        parent::__construct($num, ['type' => $realType]);
    }

    public function getTag(): string
    {
        return 'say-as';
    }

    public static function mock()
    {
        return new static(12345, self::ORDINAL);
    }

}