<?php

/**
 *
 * Flextype Minify HTML Plugin
 *
 * @author Romanenko Sergey / Awilum <awilum@yandex.ru>
 * @link http://flextype.org
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Flextype;

use Flextype\Component\Event\Event;

// Event: onCurrentPageAfterProcessed
Event::addListener('onCurrentPageAfterProcessed', function () {
    $content = ob_get_contents();
    ob_end_clean();
    echo MinifyHTML::process($content);
});

class MinifyHTML
{
    /**
     * Minify html
     *
     *  <code>
     *      echo MinifyHTML::process($html);
     *  </code>
     *
     * @param  string $buffer html
     * @return string
     */
    public static function process($html)
    {
        // Remove HTML comments (not containing IE conditional comments).
        $html = preg_replace_callback('/<!--([\\s\\S]*?)-->/', 'MinifyHTML::_comments', $html);

        // Trim each line.
        $html = preg_replace('/^\\s+|\\s+$/m', '', $html);

        // Return HTML
        return $html;
    }

    protected static function _comments($m)
    {
        return (0 === strpos($m[1], '[') || false !== strpos($m[1], '<![')) ? $m[0] : '';
    }

}
