<?php

namespace AcyMailing\Helpers;

use AcyMailing\Libraries\acymObject;

class HeaderHelper extends acymObject
{
    public function display($breadcrumb)
    {
        $header = '<div class="cell grid-x acym_vcenter">';
        $header .= $this->getBreadcrumb($breadcrumb);
        $header .= '</div>';

        return '<div id="acym_header" class="grid-x hide-for-small-only margin-bottom-1">'.$header.'</div>';
    }

    private function getBreadcrumb($breadcrumb)
    {
        $links = [];
        foreach ($breadcrumb as $oneLevel => $link) {
            if (!empty($link)) {
                $oneLevel = '<a href="'.$link.'">'.$oneLevel.'</a>';
            }
            $links[] = '<li>'.$oneLevel.'</li>';
        }

        if (count($links) > 1) {
            $links[count($links) - 1] = str_replace('<li>', '<li class="last_link cell shrink"><span class="show-for-sr">Current: </span>', $links[count($links) - 1]);
        }

        $header = '<img alt="logo" src="'.ACYM_IMAGES.'logo.jpeg" style="width: 40px;"/>';
        $header .= '<div id="acym_global_navigation" class="cell auto">
                        <nav aria-label="You are here:" role="navigation">
                            <ul class="breadcrumbs grid-x">'.implode('<li class="breadcrumbs__separator"><i class="acymicon-keyboard_arrow_right"></i></li>', $links).'</ul>
                        </nav>
                    </div>';

        return $header;
    }
}
