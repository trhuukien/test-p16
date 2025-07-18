<?php

/*
 * This file is part of PHP CS Fixer.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *     Dariusz Rumiński <dariusz.ruminski@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Biztech\Deliverydate\Model\System\Config;

use \Magento\Config\Model\Config\CommentInterface;

class Comment implements CommentInterface {

    public function getCommentText($elementValue) {  //the method has to be named getCommentText
        return __('To get the activation key, you can contact us at <a href="https://www.appjetty.com/support.htm" target="-">appjetty</a>');
    }

}
