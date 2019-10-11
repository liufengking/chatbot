<?php


namespace Commune\Components\Predefined\Intents\Attitudes;

use Commune\Chatbot\OOHost\Emotion\Emotions\Negative;

class DenyInt extends AttitudeInt implements Negative
{
    const SIGNATURE = 'deny';

    const DESCRIPTION = '否认';
}