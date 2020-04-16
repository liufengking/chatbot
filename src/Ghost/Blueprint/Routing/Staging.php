<?php

/**
 * This file is part of CommuneChatbot.
 *
 * @link     https://github.com/thirdgerb/chatbot
 * @document https://github.com/thirdgerb/chatbot/blob/master/README.md
 * @contact  <thirdgerb@gmail.com>
 * @license  https://github.com/thirdgerb/chatbot/blob/master/LICENSE
 */

namespace Commune\Ghost\Blueprint\Routing;

use Commune\Ghost\Blueprint\Operator\Operator;


/**
 * @author thirdgerb <thirdgerb@gmail.com>
 */
interface Staging
{
    public function restartContext() : Operator;

    public function resetContext() : Operator;

    /**
     * 沿着一个或者多个 Stage 的路径前进.
     *
     * @param mixed ...$stageNames
     * @return Operator
     */
    public function next(...$stageNames) : Operator;



}