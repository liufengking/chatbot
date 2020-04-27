<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Ghost\Blueprint\Stage;

use Commune\Ghost\Blueprint\Context\Context;
use Commune\Ghost\Blueprint\Convo\Conversation;
use Commune\Ghost\Blueprint\Definition\StageDef;
use Commune\Ghost\Blueprint\Routing\Fallback;
use Commune\Ghost\Blueprint\Routing\Staging;

/**
 *
 * @author thirdgerb <thirdgerb@gmail.com>
 *
 * @property-read Conversation $conversation
 * @property-read StageDef $def
 * @property-read Context $self
 * @property-read Context $from
 */
interface OnRetrace extends Stage
{
    public function staging() : Staging;

    public function fallback() : Fallback;

}