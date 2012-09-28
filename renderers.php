<?php
/* renderers to align Moodle's HTML with that expected by Bootstrap */

class html {
    // html utility functions

    public static function moodle_icon($name) {
        return bootstrap::moodle_to_bootstrap_icon($name);
    }

    public static function a($attributes, $content) {
        return html_writer::tag('a', $content, $attributes);
    }

    public static function div($attributes, $content) {
        return html_writer::tag('div', $content, $attributes);
    }

    public static function span($attributes, $content) {
        return html_writer::tag('span', $content, $attributes);
    }
    public static function ul($items) {
        return '<ul><li>'.implode($items, '</li><li>').'</li></ul>';
    }

    public static function classes($classes) {
        // don't know what this does, but it sounds useful
        return renderer_base::prepare_classes($classes);
    }

}
class bootstrap {
    // bootstrap utility functions

    static $icons = array(
            'docs' => 'question-sign',
            'book' => 'book',
            'chapter' => 'file',
            'spacer' => 'spacer',
            'generate' => 'gift',
            'add' => 'plus',
            't/hide' => 'eye-open',
            'i/hide' => 'eye-open',
            't/show' => 'eye-close',
            'i/show' => 'eye-close',
            't/add' => 'plus',
            't/right' => 'arrow-right',
            't/left' => 'arrow-left',
            't/up' => 'arrow-up',
            't/down' => 'arrow-down',
            't/edit' => 'edit',
            't/editstring' => 'tag',
            't/delete' => 'remove',
            'i/edit' => 'pencil',
            't/copy' => 'copy', // in font awesome
            'i/settings' => 'list-alt',
            'i/grades' => 'grades',
            'i/group' => 'user',
            't/switch_plus' => 'plus-sign',
            't/switch_minus' => 'minus-sign',
            'i/filter' => 'filter',
            't/move' => 'resize-vertical',
            'i/move_2d' => 'move',
            'i/backup' => 'cog',
            'i/restore' => 'cog',
            'i/return' => 'repeat',
            'i/reload' => 'refresh',
            'i/roles' => 'user',
            'i/user' => 'user',
            'i/users' => 'user',
            'i/publish' => 'publish',
            'i/navigationitem' => 'chevron-right' );

    public static function moodle_to_bootstrap_icon($name) {
        return self::icon(self::$icons[$name]);    
    }
    public static function icon($name) {
        return "<i class=icon-$name></i>";
    }
    public static function icon_help() {
        return self::icon('question-sign');
    } 
    public static function icon_spacer() {
        return self::icon('spacer');
        // no actual spacer icon provided by bootstrap, but magically it still works
    } 

    public static function label($type, $text) {
        if ($type != '') {
            $type = ' label-' . $type;
        }
        // bootstrap label classes can be added to other things
        // but are usually spans (or a tags for clickable links)
        return "<span class=\"label$type\">$text</i>";
    }
    public static function label_default($text) {
        return self::label('', $text);
    }
    public static function label_success($text) {
        return self::label('success', $text);
    }
    public static function label_warning($text) {
        return self::label('warning', $text);
    }
    public static function label_important($text) {
        return self::label('important', $text);
    }
    public static function label_info($text) {
        return self::label('info', $text);
    }
    public static function label_inverse($text) {
        return self::label('inverse', $text);
    }


    public static function badge($type, $text) {
        if ($type != '') {
            $type = ' badge-' . $type;
        }
        // bootstrap badge classes can be added to other things
        // but are usually spans (or a tags for clickable links)
        return "<span class=\"badge$type\">$text</i>";
    }
    public static function badge_default($text) {
        return self::badge('', $text);
    }
    public static function badge_success($text) {
        return self::badge('success', $text);
    }
    public static function badge_warning($text) {
        return self::badge('warning', $text);
    }
    public static function badge_important($text) {
        return self::badge('important', $text);
    }
    public static function badge_info($text) {
        return self::badge('info', $text);
    }
    public static function badge_inverse($text) {
        return self::badge('inverse', $text);
    }
    

    public static function alert($type, $text) {
        if ($type != '') {
            $type = ' alert-' . $type;
        }
        return "<div class=\"alert$type\">$text</i>";
    }
    public static function alert_default($text) {
        return self::alert('', $text);
    }
    public static function alert_success($text) {
        return self::alert('success', $text);
    }
    public static function alert_error($text) {
        return self::alert('error', $text);
    }
    public static function alert_block($text) {
        return self::alert('block', $text);
    }
    public static function alert_info($text) {
        return self::alert('info', $text);
    }


    public static function initialism($full, $short) {
        return "<abbr title=\"$full\" class=\"initialism\">$short</abbr>";
    }


    public static function unstyled_ul($items) {
        return '<ul class=unstyled>'.implode($items, '</li><li>').'</ul>';
    }
}

class theme_bootstrap_renderers_core_renderer extends core_renderer {
    // trying to keep the order of definition the same as
    // the source file, lib/outputrenderers.php

    public function doctype() {
        $this->contenttype = 'text/html; charset=utf-8';
        return "<!DOCTYPE html>";
    }
    public function htmlattributes() {
        return get_html_lang(true);
    }
    // public function standard_head_html() {}
    // lots of stuff going on here, should really be split up

    // public function standard_footer_html() {}
    // same as head, should be split

    // public function main_content() {}
    // could be a chance to wrap the main_content

    public function login_info() {
        // this could probably be tidied up
        // bit confusing at the moment
        //
        // also gets outputted in header and footer
        // by default, probably want to do entirely different
        // things in each place

        global $USER, $CFG, $DB, $SESSION;

        if (during_initial_install()) {
            return '';
        }

        $loginpage = ((string)$this->page->url === get_login_url());
        $course = $this->page->course;

        if (session_is_loggedinas()) {
            $realuser = session_get_realuser();
            $fullname = fullname($realuser, true);
            $realuserinfo = "[<a href=\"$CFG->wwwroot/course/loginas.php?id=$course->id&amp;sesskey=".sesskey()."\" class=navbar-link>$fullname</a>]";
        } else {
            $realuserinfo = '';
        }

        $loginurl = get_login_url();

        if (empty($course->id)) {
            // $course->id is not defined during installation
            return '';
        } else if (isloggedin()) {
            $context = get_context_instance(CONTEXT_COURSE, $course->id);

            $fullname = fullname($USER, true);
            // Since Moodle 2.0 this link always goes to the public profile page (not the course profile page)
            $username = "<a href=\"$CFG->wwwroot/user/profile.php?id=$USER->id\" class=navbar-link>$fullname</a>";
            if (is_mnet_remote_user($USER) and $idprovider = $DB->get_record('mnet_host', array('id'=>$USER->mnethostid))) {
                $username .= " from <a href=\"{$idprovider->wwwroot}\" class=navbar-link>{$idprovider->name}</a>";
            }
            if (isguestuser()) {
                $loggedinas = $realuserinfo.get_string('loggedinasguest');
                if (!$loginpage) {
                    $loggedinas .= " (<a href=\"$loginurl\" class=navbar-link>".get_string('login').'</a>)';
                }
            } else if (is_role_switched($course->id)) { // Has switched roles
                $rolename = '';
                if ($role = $DB->get_record('role', array('id'=>$USER->access['rsw'][$context->path]))) {
                    $rolename = ': '.format_string($role->name);
                }
                $loggedinas = get_string('loggedinas', 'moodle', $username).$rolename.
                          " (<a href=\"$CFG->wwwroot/course/view.php?id=$course->id&amp;switchrole=0&amp;sesskey=".sesskey()."\" class=navbar-link>".get_string('switchrolereturn').'</a>)';
            } else {
                $loggedinas = $realuserinfo.get_string('loggedinas', 'moodle', $username).' '.
                          " (<a href=\"$CFG->wwwroot/login/logout.php?sesskey=".sesskey()."\" class=navbar-link>".get_string('logout').'</a>)';
            }
        } else {
            if ($loginpage) {
                $loggedinas = get_string('loggedinnot', 'moodle');
            } else {
                $loggedinas .= '<input class="span2" type="text" placeholder="username">
              <input class="span2" type="password" placeholder="password">
              <button type="submit" class="btn">'.get_string('login').'</button>';
            }
        }

        if (isset($SESSION->justloggedin)) {
            unset($SESSION->justloggedin);
            if (!empty($CFG->displayloginfailures)) {
                if (!isguestuser()) {
                    if ($count = count_login_failures($CFG->displayloginfailures, $USER->username, $USER->lastlogin)) {
                        $loggedinas .= '&nbsp;<div class="loginfailures">';
                        if (empty($count->accounts)) {
                            $loggedinas .= get_string('failedloginattempts', '', $count);
                        } else {
                            $loggedinas .= get_string('failedloginattemptsall', '', $count);
                        }
                        if (file_exists("$CFG->dirroot/report/log/index.php") and has_capability('report/log:view', get_context_instance(CONTEXT_SYSTEM))) {
                            $loggedinas .= ' (<a href="'.$CFG->wwwroot.'/report/log/index.php'.
                                                 '?chooselog=1&amp;id=1&amp;modid=site_errors" class=navbar-link>'.get_string('logs').'</a>)';
                        }
                        $loggedinas .= '</div>';
                    }
                }
            }
        }

        return $loggedinas;
    }

    public function home_link() {
        global $CFG, $SITE;
        $text = '';
        $linktext = 'Moodle';

        if ($this->page->pagetype == 'site-index') {
            $div_attributes['class'] = "sitelink";
            $text = 'Made with ';
            $a_attributes['href'] = 'http://moodle.org/';
            $a_attributes['class'] = 'label';
            $a_attributes['style'] = 'background-color: orange;';
        } else if (!empty($CFG->target_release) &&
                $CFG->target_release != $CFG->release) {
            // Special case for during install/upgrade.
            $div_attributes['class'] = "sitelink";
            $text = 'help with ';
            $a_attributes['href'] = 'http://docs.moodle.org/en/Administrator_documentation';
            $a_attributes['target'] = '_blank';
        } else if ($this->page->course->id == $SITE->id ||
                strpos($this->page->pagetype, 'course-view') === 0) {
            $div_attributes['class'] = "homelink";
            $linktext = get_string('home');
            $a_attributes['href'] = $CFG->wwwroot . '/';
        } else {
            $div_attributes['class'] = "homelink";
            $linktext = format_string($this->page->course->shortname, true, array('context' => $this->page->context));
            $a_attributes['href'] = $CFG->wwwroot . '/course/view.php?id=' . $this->page->course->id;
        }
        return html::div($div_attributes, $text . html::a($a_attributes, $linktext));
    }

    //public function redirect_message($encodedurl, $message, $delay, $debugdisableredirect) {
    // there's an error message that could be bootstrapped, but it's buried
    // under a lot of other stuff, low priority I think

    public function block_controls($controls) {
        if (empty($controls)) {
            return '';
        }
        $controlshtml = array();
        foreach ($controls as $control) {
            $controlshtml[] = html::a(array('href'=>$control['url'], 'title'=>$control['caption']), html::moodle_icon($control['icon']));
        }
        return html::div(array('class'=>'commands'), implode($controlshtml, ' '));
    }

    public function block(block_contents $bc, $region) {
        // trying to make each block a list, first item the header, second items controls,
        // then if content is a list just join on and close the ul in the footer
        // don't know if it'll work, Boostrap just expects simple lists

        // rename class invisible to dimmed
        $bc->attributes['class'] = str_replace ('invisible', 'dimmed', $bc->attributes['class']);

        $bc = clone($bc); // Avoid messing up the object passed in.
        if (empty($bc->blockinstanceid) || !strip_tags($bc->title)) {
            $bc->collapsible = block_contents::NOT_HIDEABLE;
        }
        if ($bc->collapsible == block_contents::HIDDEN) {
            $bc->add_class('hidden');
        }
        if (!empty($bc->controls)) {
            $bc->add_class('block_with_controls');
        }
        $bc->add_class('well'); // bootstrap style
        $bc->attributes['style'] = 'padding: 8px 0;'; // bit strange of bootstrap to hard code this but that's what the example does

        $skiptitle = strip_tags($bc->title);
        if (empty($skiptitle)) {
            $output = '';
            $skipdest = '';
        } else {
            $output = html_writer::tag('a', get_string('skipa', 'access', $skiptitle), array('href' => '#sb-' . $bc->skipid, 'class' => 'skip-block'));
            $skipdest = html_writer::tag('span', '', array('id' => 'sb-' . $bc->skipid, 'class' => 'skip-block-to'));
        }

        $output .= html_writer::start_tag('div', $bc->attributes);

        $output .= $this->block_header($bc);
        $output .= $this->block_content($bc);

        $output .= html_writer::end_tag('div');

        $output .= $this->block_annotation($bc);

        $output .= $skipdest;

        $this->init_block_hider_js($bc);
        return $output;
    }

    protected function block_header(block_contents $bc) {
        $output = '<ul class="nav nav-list">';

        if ($bc->title) {
            $output .= "<li class=nav-header>$bc->title</li>";
        }

        if ($bc->controls) {
            $output .= '<li>' . $this->block_controls($bc->controls) . '</li>';
        }

        return $output;
    }

    protected function block_content(block_contents $bc) {
        // probably only working for lists at the moment
        $output = $bc->content;
        $output .= $this->block_footer($bc);

        return $bc->content . $this->block_footer($bc);
    }

    protected function block_footer(block_contents $bc) {
        $output = '';
        if ($bc->footer) {
            $output .= html_writer::tag('li', $bc->footer);
        }
        return $output . '</ul>';
    }

    public function list_block_contents($icons, $items) {
        // currently just ditches icons rather than convert them
        return '<li>' . implode($items, '</li><li>') . '</li>';
    }

    public function action_icon($url, pix_icon $pixicon, component_action $action = null, array $attributes = null, $linktext=false) {
        if (!($url instanceof moodle_url)) {
            $url = new moodle_url($url);
        }
        $attributes = (array)$attributes;

        if (empty($attributes['class'])) {
            // let ppl override the class via $options
            $attributes['class'] = 'action-icon';
        }

        $icon = $this->render($pixicon);

        $attributes['title'] = $pixicon->attributes['alt'];
        // bootstrap icons aren't img tags, so don't have alt tags
        // add the alt text as title to surrounding a tag instead

        if ($linktext) {
            $text = $pixicon->attributes['alt'];
        } else {
            $text = '';
        }

        return $this->action_link($url, $text.$icon, $action, $attributes);
    }

    public function confirm($message, $continue, $cancel) {
        // changed this but not sure where it's used so not tested

        if ($continue instanceof single_button) {
            // ok
        } else if (is_string($continue)) {
            $continue = new single_button(new moodle_url($continue), get_string('continue'), 'post');
        } else if ($continue instanceof moodle_url) {
            $continue = new single_button($continue, get_string('continue'), 'post');
        } else {
            throw new coding_exception('The continue param to $OUTPUT->confirm() must be either a URL (string/moodle_url) or a single_button instance.');
        }

        if ($cancel instanceof single_button) {
            // ok
        } else if (is_string($cancel)) {
            $cancel = new single_button(new moodle_url($cancel), get_string('cancel'), 'get');
        } else if ($cancel instanceof moodle_url) {
            $cancel = new single_button($cancel, get_string('cancel'), 'get');
        } else {
            throw new coding_exception('The cancel param to $OUTPUT->confirm() must be either a URL (string/moodle_url) or a single_button instance.');
        }

        return bootstrap::alert_box("<p>$message</p>" .  
            html::div( $this->render($continue) . $this->render($cancel))
        );
    }

    protected function render_single_button(single_button $button) {
        $attributes = array('type'     => 'submit',
                'class'    => 'btn',
                'value'    => $button->label,
                'disabled' => $button->disabled ? 'disabled' : null,
                'title'    => $button->tooltip);

        if ($button->actions) {
            $id = html_writer::random_id('single_button');
            $attributes['id'] = $id;
            foreach ($button->actions as $action) {
                $this->add_action_handler($action, $id);
            }
        }

        // first the input element
        $output = html_writer::empty_tag('input', $attributes);

        // then hidden fields
        $params = $button->url->params();
        if ($button->method === 'post') {
            $params['sesskey'] = sesskey();
        }
        foreach ($params as $var => $val) {
            $output .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => $var, 'value' => $val));
        }

        // then div wrapper for xhtml strictness
        $output = html_writer::tag('div', $output);

        // now the form itself around it
        if ($button->method === 'get') {

            $url = $button->url->out_omit_querystring(true); // url without params, the anchor part allowed
        } else {
            $url = $button->url->out_omit_querystring();     // url without params, the anchor part not allowed
        }
        if ($url === '') {
            $url = '#'; // there has to be always some action
        }
        $attributes = array('method' => $button->method,
                'class' => 'form-inline',
                'action' => $url,
                'id'     => $button->formid);
        $output = html_writer::tag('form', $output, $attributes);

        return html::div(array('class' => $button->class), $output);
    }

    protected function render_single_select(single_select $select) {
        $select = clone($select);
        if (empty($select->formid)) {
            $select->formid = html_writer::random_id('single_select_f');
        }

        $output = '';
        $params = $select->url->params();
        if ($select->method === 'post') {
            $params['sesskey'] = sesskey();
        }
        foreach ($params as $name=>$value) {
            $output .= html_writer::empty_tag('input', array('type'=>'hidden', 'name'=>$name, 'value'=>$value));
        }

        if (empty($select->attributes['id'])) {
            $select->attributes['id'] = html_writer::random_id('single_select');
        }

        if ($select->disabled) {
            $select->attributes['disabled'] = 'disabled';
        }

        if ($select->tooltip) {
            $select->attributes['title'] = $select->tooltip;
        }

        if ($select->label) {
            $output .= html_writer::label($select->label, $select->attributes['id'], false, $select->labelattributes);
        }

        if ($select->helpicon instanceof help_icon) {
            $output .= $this->render($select->helpicon);
        } else if ($select->helpicon instanceof old_help_icon) {
            $output .= $this->render($select->helpicon);
        }
        $output .= html_writer::select($select->options, $select->name, $select->selected, $select->nothing, $select->attributes);

        $go = html_writer::empty_tag('input', array('class'=>'btn','type'=>'submit', 'value'=>get_string('go')));
        $output .= html_writer::tag('noscript', html_writer::tag('div', $go), array('style'=>'inline'));

        $nothing = empty($select->nothing) ? false : key($select->nothing);
        $this->page->requires->js_init_call('M.util.init_select_autosubmit', array($select->formid, $select->attributes['id'], $nothing));

        // then div wrapper for xhtml strictness
        $output = html_writer::tag('div', $output);

        // now the form itself around it
        if ($select->method === 'get') {
            $url = $select->url->out_omit_querystring(true); // url without params, the anchor part allowed
        } else {
            $url = $select->url->out_omit_querystring();     // url without params, the anchor part not allowed
        }
        $formattributes = array('method' => $select->method,
                                'class' => 'form-inline',
                                'action' => $url,
                                'id'     => $select->formid);
        $output = html_writer::tag('form', $output, $formattributes);

        // and finally one more wrapper with class
        return html_writer::tag('div', $output, array('class' => $select->class));
    }

    // protected function render_url_select(url_select $select) {
    // probably needs a .form-inline for the 'go' button
    // but too scary to deal with right now

    public function doc_link($path, $text = '') {
        $attributes['href'] = new moodle_url(get_docs_url($path));
        if ($text == '') {
            $linktext = bootstrap::icon_help();
        } else {
            $linktext = bootstrap::icon_help().' '.$text; }
        return html::a($attributes, $linktext);
    }

    protected function render_pix_icon(pix_icon $icon) {

        if (isset(bootstrap::$icons[$icon->pix])) {
            return bootstrap::icon(bootstrap::$icons[$icon->pix]);
            // currently throws away any attributes attached to
            // the icon, like alt, which could be rendered
            // using .hide-text image replacement technique

            // also doesn't look at the $icon->component, so all mod
            // icons for example look the same as pix == 'icon'
        } else {
            return parent::render_pix_icon($icon);
        }
    }

    // function render_rating(rating $rating) {
    // theres some buttons and form labels in here that
    // could be restyled with .btn and .form-inline probably

    public function heading_with_help($text, $helpidentifier, $component = 'moodle', $icon = '', $iconalt = '') {
        if ($icon) {
            // should be done via CSS, if at all
        }

        $help = '';
        if ($helpidentifier) {
            $help = $this->help_icon($helpidentifier, $component);
        }

        return "<h2>$text $help</h2>";
    }

    protected function render_help_icon(help_icon $helpicon) {
        global $CFG;

        $title = get_string($helpicon->identifier, $helpicon->component);

        if (empty($helpicon->linktext)) {
            $alt = get_string('helpprefix2', '', trim($title, ". \t"));
        } else {
            $alt = get_string('helpwiththis');
        }

        $output = bootstrap::icon_help();

        // add the link text if given
        if (!empty($helpicon->linktext)) {
            $output .= ' '.$helpicon->linktext;
        }

        // now create the link around it - we need https on loginhttps pages
        $url = new moodle_url($CFG->httpswwwroot.'/help.php', array('component' => $helpicon->component, 'identifier' => $helpicon->identifier, 'lang'=>current_language()));

        // note: this title is displayed only if JS is disabled, otherwise the link will have the new ajax tooltip
        $title = get_string('helpprefix2', '', trim($title, ". \t"));

        $attributes = array('href'=>$url, 'title'=>$title);
        $id = html_writer::random_id('helpicon');
        $attributes['id'] = $id;
        $output = html_writer::tag('a', $output, $attributes);

        $this->page->requires->js_init_call('M.util.help_icon.add', array(array('id'=>$id, 'url'=>$url->out(false))));

        // and finally span
        return html_writer::tag('span', $output, array('class' => 'helplink'));
        // final span probably unnecessary but leaving it in case the js needs it
    }

    public function spacer(array $attributes = null, $br = false) {
        return bootstrap::icon_spacer();
        // don't bother outputting br's or attributes
    }

    // protected function render_user_picture(user_picture $userpicture) {
    // could add a nice frame effect on the image

    // public function render_file_picker(file_picker $fp) {
    // there's a button in here, but it appears to be display:none'd

    public function error_text($message) {
        // default implementation uses a span, not a div
        // maybe this maps better to a bootstrap .label?

        if (empty($message)) { return ''; }
        return bootstrap::alert_error($message);
    }

    // public function fatal_error($message, $moreinfourl, $link, $backtrace, $debuginfo = null) {
    // there's some error notices that could be put in alerts here

    public function notification($message, $classes = 'notifyproblem') {
        // TODO rewrite recognized classnames to bootstrap alert equivalent 
        // only two are mentioned in documentation, there may be more

        $message = clean_text($message);

        if ($classes = 'notifyproblem') {
            return bootstrap::alert_error($message);
        }
        if ($classes = 'notifysuccess') {
            return bootstrap::alert_success($message);
        }
    }

    // public function continue_button($url) {
    // not sure we need a class on this,
    // but doesn't seem worth rewriting just for that

    protected function render_paging_bar(paging_bar $pagingbar) {
        // this is more complicated than it needs to be, see MDL-35367 

        $pagingbar->maxdisplay = 11; // odd number for symmetry
        $pagingbar = clone($pagingbar);
        $pagingbar->prepare($this, $this->page, $this->target);
        $show_pagingbar = ($pagingbar->totalcount > $pagingbar->perpage);
        if ($show_pagingbar) {
            $baseurl = $pagingbar->baseurl;
            $pagevar = $pagingbar->pagevar;
            $maxdisplay = max($pagingbar->maxdisplay, 5);
            $page = $pagingbar->page;

            $output = '<div class="pagination pagination-centered"><ul>';

            // Note: page 0 is displayed to users as page 1 and so on.
            if ($pagingbar->perpage > 0) {
                $lastpage = floor($pagingbar->totalcount / $pagingbar->perpage);
            } else {
                $lastpage = 0;
            }
            if ($page != 0) {
                $previouslink = html_writer::link(new moodle_url($baseurl, array($pagevar=>$page-1)), get_string('previous'));
                $output .= "<li>$previouslink</li>";
            } else {
                $output .= '<li class=disabled><span>'.get_string('previous').'</span></li>';
            }

            $start = 0;
            $stop = $lastpage;
            $truncate = $lastpage + 1 > $maxdisplay ;
            $start_margin = floor($maxdisplay / 2);
            $end_margin = $lastpage - ceil($maxdisplay / 2);
            $near_to_start = $page < $start_margin;
            $near_to_end = $page > $end_margin;
            if ($truncate && $near_to_start) {
                $stop = $maxdisplay - 3;
            } else if ($truncate && $near_to_end) {
                $start = $lastpage - $maxdisplay + 3;
            } else if ($truncate) { // truncate both sides, centered on current page
                $before_current = ceil(($maxdisplay - 5) / 2) ;
                $start = $page - $before_current;
                $stop = $start + $maxdisplay - 5;
            }

            if ($truncate && !$near_to_start) {
                $link = html_writer::link(new moodle_url($baseurl, array($pagevar=>'0')), '1');
                $output .= "<li>$link</li>" . "<li class=disabled><span>…</span></li>";
            }

            for ($i = $start; $i <= $stop; $i++) {
                if ($page == $i) {
                    $pagename = $page + 1;
                    $output .= "<li class=active><span>$pagename</span></li>";
                } else {
                    $link = html_writer::link(new moodle_url($baseurl, array($pagevar=>$i)), $i+1);
                    $output .= "<li>$link</li>";
                }
            }

            if ($truncate && !$near_to_end) {
                $output .= "<li class=disabled><span>…</span>";
                $link = html_writer::link(new moodle_url($baseurl, array($pagevar=>$lastpage)), $lastpage + 1);
                $output .= "<li>$link</li>";
            }

            if ($page != $lastpage) {
                $nextlink = html_writer::link(new moodle_url($baseurl, array($pagevar=>$page+1)), get_string('next'));
                $output .= "<li>$nextlink</li>";
            } else {
                $output .= '<li class=disabled><span>'.get_string('next').'</span></li>';
            }

            return $output."</ul></div>";
        }
    }
    // public function skip_link_target($id = null) {
    // I think this should usually point to an id on the actual
    // content rather than an extra span stuck in before it, but
    // that's not really Bootstrap related

    // public function heading($text, $level = 2, $classes = 'main', $id = null) {
    // might be nice to allow Bootstrap-style sub-headings using <small>
    // or maybe that works anyway if you put the tags in the header text?

    //public function box($contents, $classes = 'generalbox', $id = null) {
    // 99% of these could probably be replaced with a classless div
    // maybe only output classes and ids if specified?

    // public function container($contents, $classes = null, $id = null) {
    // not sure of semantic difference between container, box and div


    // public function tree_block_contents($items, $attrs = array()) {
    // looks important, but a lot going on

    public function navbar() {
        // bit of a nameclash, Bootstrap calls the navbar the breadcrumb and
        // also have a sperate thing called navbar that sticks to the top of the page

        $items = $this->page->navbar->get_items();
        foreach ($items as $item) {
            $item->hideicon = true;
            $links[] = $this->render($item);
        }
        $divider = '<span class=divider>/</span>';
        return '<ul class=breadcrumb><li>' . join($links, " $divider</li><li>") . '</li></ul>';
        return $navbarcontent;
    }

    protected function render_custom_menu(custom_menu $menu) {
        if (!$menu->has_children()) {
            return '';
        }
        $content  = '<div class="navbar navbar-fixed-top">' .
        '<div class=navbar-inner>' .
        '<div class=container>' .
        '<ul class=nav>';

        foreach ($menu->get_children() as $item) {
            $content .= $this->render_custom_menu_item($item);
        }
        $content .= '</ul></div></div><div>'; 
        return $content;
    }

    protected function render_custom_menu_item(custom_menu_item $menunode) {
        // Required to ensure we get unique trackable id's
        static $submenucount = 0;

        if ($menunode->has_children()) {
            $content = '<li class=dropdown>';
            // If the child has menus render it as a sub menu
            $submenucount++;
            if ($menunode->get_url() !== null) {
                $url = $menunode->get_url();
            } else {
                $url = '#cm_submenu_'.$submenucount;
            }

            //$content .= html_writer::link($url, $menunode->get_text(), array('title'=>,));
            $content .= '<a href="'.$url.'" class=dropdown-toggle data-toggle=dropdown>';
            $content .= $menunode->get_title();
            $content .= '<b class=caret></b></a>';
            $content .= '<ul class=dropdown-menu>';
            foreach ($menunode->get_children() as $menunode) {
                $content .= $this->render_custom_menu_item($menunode);
            }
            $content .= '</ul>';
        } else {
            $content = '<li>';
            // The node doesn't have children so produce a final menuitem

            if ($menunode->get_url() !== null) {
                $url = $menunode->get_url();
            } else {
                $url = '#';
            }
            $content .= html_writer::link($url, $menunode->get_text(), array('title'=>$menunode->get_title()));
        }
        $content .= '<li>';
        return $content;
    }

}

    include_once($CFG->dirroot . "/admin/renderer.php");
class theme_bootstrap_renderers_core_admin_renderer extends core_admin_renderer {
         
    /**
     * Display the 'Do you acknowledge the terms of the GPL' page. The first page
     * during install.
     * @return string HTML to output.
     */
    public function install_licence_page() {
        global $CFG;
        $output = '';

        $copyrightnotice = text_to_html(get_string('gpl3'));
        $copyrightnotice = str_replace('target="_blank"', 'onclick="this.target=\'_blank\'"', $copyrightnotice); // extremely ugly validation hack

        $continue = new single_button(new moodle_url('/admin/index.php', array('lang'=>$CFG->lang, 'agreelicense'=>1)), get_string('continue'), 'get');

        $output .= $this->header();
        $output .= $this->heading('<a href="http://moodle.org">Moodle</a> - Modular Object-Oriented Dynamic Learning Environment');
        $output .= $this->heading(get_string('copyrightnotice'));
        $output .= $this->box($copyrightnotice, 'copyrightnotice');
        $output .= html_writer::empty_tag('br');
        $output .= $this->confirm(get_string('doyouagree'), $continue, "http://docs.moodle.org/dev/License");
        $output .= $this->footer();

        return $output;
    }

    /**
     * Display page explaining proper upgrade process,
     * there can not be any PHP file leftovers...
     *
     * @return string HTML to output.
     */
    public function upgrade_stale_php_files_page() {
        $output = '';
        $output .= $this->header();
        $output .= $this->heading(get_string('upgradestalefiles', 'admin'));
        $output .= $this->box_start('generalbox', 'notice');
        $output .= format_text(get_string('upgradestalefilesinfo', 'admin', get_docs_url('Upgrading')), FORMAT_MARKDOWN);
        $output .= html_writer::empty_tag('br');
        $output .= html_writer::tag('div', $this->single_button($this->page->url, get_string('reload'), 'get'), array('class' => 'buttons'));
        $output .= $this->box_end();
        $output .= $this->footer();

        return $output;
    }

    /**
     * Display the 'environment check' page that is displayed during install.
     * @param int $maturity
     * @param boolean $envstatus final result of the check (true/false)
     * @param array $environment_results array of results gathered
     * @param string $release moodle release
     * @return string HTML to output.
     */
    public function install_environment_page($maturity, $envstatus, $environment_results, $release) {
        global $CFG;
        $output = '';

        $output .= $this->header();
        $output .= $this->maturity_warning($maturity);
        $output .= $this->heading("Moodle $release");
        $output .= $this->release_notes_link();

        $output .= $this->environment_check_table($envstatus, $environment_results);

        if (!$envstatus) {
            $output .= $this->upgrade_reload(new moodle_url('/admin/index.php', array('agreelicense' => 1, 'lang' => $CFG->lang)));
        } else {
            $output .= $this->notification(get_string('environmentok', 'admin'), 'notifysuccess');
            $output .= $this->continue_button(new moodle_url('/admin/index.php', array('agreelicense'=>1, 'confirmrelease'=>1, 'lang'=>$CFG->lang)));
        }

        $output .= $this->footer();
        return $output;
    }

    /**
     * Displays the list of plugins with unsatisfied dependencies
     *
     * @param double|string|int $version Moodle on-disk version
     * @param array $failed list of plugins with unsatisfied dependecies
     * @param moodle_url $reloadurl URL of the page to recheck the dependencies
     * @return string HTML
     */
    public function unsatisfied_dependencies_page($version, array $failed, moodle_url $reloadurl) {
        $output = '';

        $output .= $this->header();
        $output .= $this->heading(get_string('pluginscheck', 'admin'));
        $output .= $this->warning(get_string('pluginscheckfailed', 'admin', array('pluginslist' => implode(', ', array_unique($failed)))));
        $output .= $this->plugins_check_table(plugin_manager::instance(), $version, array('xdep' => true));
        $output .= $this->warning(get_string('pluginschecktodo', 'admin'));
        $output .= $this->continue_button($reloadurl);

        $output .= $this->footer();

        return $output;
    }

    /**
     * Display the 'You are about to upgrade Moodle' page. The first page
     * during upgrade.
     * @param string $strnewversion
     * @param int $maturity
     * @return string HTML to output.
     */
    public function upgrade_confirm_page($strnewversion, $maturity) {
        $output = '';

        $continueurl = new moodle_url('index.php', array('confirmupgrade' => 1));
        $cancelurl = new moodle_url('index.php');

        $output .= $this->header();
        $output .= $this->maturity_warning($maturity);
        $output .= $this->confirm(get_string('upgradesure', 'admin', $strnewversion), $continueurl, $cancelurl);
        $output .= $this->footer();

        return $output;
    }

    /**
     * Display the environment page during the upgrade process.
     * @param string $release
     * @param boolean $envstatus final result of env check (true/false)
     * @param array $environment_results array of results gathered
     * @return string HTML to output.
     */
    public function upgrade_environment_page($release, $envstatus, $environment_results) {
        global $CFG;
        $output = '';

        $output .= $this->header();
        $output .= $this->heading("Moodle $release");
        $output .= $this->release_notes_link();
        $output .= $this->environment_check_table($envstatus, $environment_results);

        if (!$envstatus) {
            $output .= $this->upgrade_reload(new moodle_url('/admin/index.php'), array('confirmupgrade' => 1));

        } else {
            $output .= $this->notification(get_string('environmentok', 'admin'), 'notifysuccess');

            if (empty($CFG->skiplangupgrade) and current_language() !== 'en') {
                $output .= $this->box(get_string('langpackwillbeupdated', 'admin'), 'generalbox', 'notice');
            }

            $output .= $this->continue_button(new moodle_url('/admin/index.php', array('confirmupgrade' => 1, 'confirmrelease' => 1)));
        }

        $output .= $this->footer();

        return $output;
    }

    /**
     * Display the upgrade page that lists all the plugins that require attention.
     * @param plugin_manager $pluginman provides information about the plugins.
     * @param available_update_checker $checker provides information about available updates.
     * @param int $version the version of the Moodle code from version.php.
     * @param bool $showallplugins
     * @param moodle_url $reloadurl
     * @param moodle_url $continueurl
     * @return string HTML to output.
     */
    public function upgrade_plugin_check_page(plugin_manager $pluginman, available_update_checker $checker,
            $version, $showallplugins, $reloadurl, $continueurl) {
        global $CFG;

        $output = '';

        $output .= $this->header();
        $output .= $this->box_start('generalbox');
        $output .= $this->container_start('generalbox', 'notice');
        $output .= html_writer::tag('p', get_string('pluginchecknotice', 'core_plugin'));
        if (empty($CFG->disableupdatenotifications)) {
            $output .= $this->container_start('checkforupdates');
            $output .= $this->single_button(new moodle_url($reloadurl, array('fetchupdates' => 1)), get_string('checkforupdates', 'core_plugin'));
            if ($timefetched = $checker->get_last_timefetched()) {
                $output .= $this->container(get_string('checkforupdateslast', 'core_plugin',
                    userdate($timefetched, get_string('strftimedatetime', 'core_langconfig'))));
            }
            $output .= $this->container_end();
        }
        $output .= $this->container_end();

        $output .= $this->plugins_check_table($pluginman, $version, array('full' => $showallplugins));
        $output .= $this->box_end();
        $output .= $this->upgrade_reload($reloadurl);

        if ($pluginman->some_plugins_updatable()) {
            $output .= $this->container_start('upgradepluginsinfo');
            $output .= $this->help_icon('upgradepluginsinfo', 'core_admin', get_string('upgradepluginsfirst', 'core_admin'));
            $output .= $this->container_end();
        }

        $button = new single_button($continueurl, get_string('upgradestart', 'admin'), 'get');
        $button->class = 'continuebutton';
        $output .= $this->render($button);
        $output .= $this->footer();

        return $output;
    }


    /**
     * Output a warning message, of the type that appears on the admin notifications page.
     * @param string $message the message to display.
     * @param string $type type class
     * @return string HTML to output.
     */
    protected function warning($message, $type = '') {
        if ($type == 'error') { $type = ' alert-error';}
        return html_writer::tag('div', $message, array('class'=>('alert' . $type)));
    }


    /**
     * Display a warning about installing development code if necesary.
     * @param int $maturity
     * @return string HTML to output.
     */
    protected function maturity_warning($maturity) {
        if ($maturity == MATURITY_STABLE) {
            return ''; // No worries.
        }

        $maturitylevel = get_string('maturity' . $maturity, 'admin');
        return html_writer::tag('div',
                    $this->container(get_string('maturitycorewarning', 'admin', $maturitylevel)) .
                    $this->container($this->doc_link('admin/versions', get_string('morehelp'))),
                'alert maturitywarning');
    }

    /**
     * Output the copyright notice.
     * @return string HTML to output.
     */
    protected function moodle_copyright() {
        global $CFG;

        //////////////////////////////////////////////////////////////////////////////////////////////////
        ////  IT IS ILLEGAL AND A VIOLATION OF THE GPL TO HIDE, REMOVE OR MODIFY THIS COPYRIGHT NOTICE ///
        $copyrighttext = '<p><a href="http://moodle.org/">Moodle</a> '.
                         '<a href="http://docs.moodle.org/dev/Releases" title="'.$CFG->version.'">'.$CFG->release.'</a></p>'.
                         '<p>Copyright &copy; 1999 onwards, Martin Dougiamas '.
                         'and <a href="http://docs.moodle.org/dev/Credits">many other contributors</a>.</p>'.
                         '<p><a href="http://docs.moodle.org/dev/License">GNU Public License</a><p>';
        //////////////////////////////////////////////////////////////////////////////////////////////////
        return html_writer::tag('div', $copyrighttext, array('class'=>'alert alert-info copyright'));
    }

    /**
     * Display a warning about installing development code if necesary.
     * @param int $maturity
     * @return string HTML to output.
     */
    protected function maturity_info($maturity) {
        if ($maturity == MATURITY_STABLE) {
            return ''; // No worries.
        }

        $maturitylevel = get_string('maturity' . $maturity, 'admin');
        return $this->box(
                    get_string('maturitycoreinfo', 'admin', $maturitylevel) . ' ' .
                    $this->doc_link('admin/versions', get_string('morehelp')),
                'alert maturityinfo maturity'.$maturity);
    }

    /**
     * Displays the info about available Moodle updates
     *
     * @param array|null $updates array of available_update_info objects or null
     * @param int|null $fetch timestamp of the most recent updates fetch or null (unknown)
     * @return string
     */
    protected function available_updates($updates, $fetch) {

        $updateinfo = $this->box_start('alert alert-info availableupdatesinfo');
        if (is_array($updates)) {
            $updateinfo .= $this->heading(get_string('updateavailable', 'core_admin'), 3);
            foreach ($updates as $update) {
                $updateinfo .= $this->moodle_available_update_info($update);
            }
        } else {
            $now = time();
            if ($fetch and ($fetch <= $now) and ($now - $fetch < HOURSECS)) {
                $updateinfo .= $this->heading(get_string('updateavailablenot', 'core_admin'), 3);
            }
        }

        $updateinfo .= $this->container_start('checkforupdates');
        $updateinfo .= $this->single_button(new moodle_url($this->page->url, array('fetchupdates' => 1)), get_string('checkforupdates', 'core_plugin'));
        if ($fetch) {
            $updateinfo .= $this->container(get_string('checkforupdateslast', 'core_plugin',
                userdate($fetch, get_string('strftimedatetime', 'core_langconfig'))));
        }
        $updateinfo .= $this->container_end();

        $updateinfo .= $this->box_end();

        return $updateinfo;
    }


    function upgrade_reload($url) {
        return '<div><a class=btn href="' . $url. '"><i class=icon-refresh></i> ' . get_string('reload') . '</a></div>';
    }

    /**
     * Displays all known plugins and information about their installation or upgrade
     *
     * This default implementation renders all plugins into one big table. The rendering
     * options support:
     *     (bool)full = false: whether to display up-to-date plugins, too
     *     (bool)xdep = false: display the plugins with unsatisified dependecies only
     *
     * @param plugin_manager $pluginman provides information about the plugins.
     * @param int $version the version of the Moodle code from version.php.
     * @param array $options rendering options
     * @return string HTML code
     */
    public function plugins_check_table(plugin_manager $pluginman, $version, array $options = array()) {
        global $CFG;

        $plugininfo = $pluginman->get_plugins();

        if (empty($plugininfo)) {
            return '';
        }

        $options['full'] = isset($options['full']) ? (bool)$options['full'] : false;
        $options['xdep'] = isset($options['xdep']) ? (bool)$options['xdep'] : false;

        $table = new html_table();
        $table->id = 'plugins-check';
        $table->head = array(
            get_string('displayname', 'core_plugin'),
            get_string('rootdir', 'core_plugin'),
            get_string('source', 'core_plugin'),
            get_string('versiondb', 'core_plugin'),
            get_string('versiondisk', 'core_plugin'),
            get_string('requires', 'core_plugin'),
            get_string('status', 'core_plugin'),
        );
        $table->colclasses = array(
            'displayname', 'rootdir', 'source', 'versiondb', 'versiondisk', 'requires', 'status',
        );
        $table->data = array();

        $numofhighlighted = array();    // number of highlighted rows per this subsection

        foreach ($plugininfo as $type => $plugins) {

            $header = new html_table_cell($pluginman->plugintype_name_plural($type));
            $header->header = true;
            $header->colspan = count($table->head);
            $header = new html_table_row(array($header));
            $header->attributes['class'] = 'plugintypeheader type-' . $type;

            $numofhighlighted[$type] = 0;

            if (empty($plugins) and $options['full']) {
                $msg = new html_table_cell(get_string('noneinstalled', 'core_plugin'));
                $msg->colspan = count($table->head);
                $row = new html_table_row(array($msg));
                $row->attributes['class'] .= 'warning msg-noneinstalled';
                $table->data[] = $header;
                $table->data[] = $row;
                continue;
            }

            $plugintyperows = array();

            foreach ($plugins as $name => $plugin) {
                $row = new html_table_row();
                $row->attributes['class'] = 'type-' . $plugin->type . ' name-' . $plugin->type . '_' . $plugin->name;

                if ($this->page->theme->resolve_image_location('icon', $plugin->type . '_' . $plugin->name)) {
                    $icon = $this->output->pix_icon('icon', '', $plugin->type . '_' . $plugin->name, array('class' => 'smallicon pluginicon'));
                } else {
                    $icon = bootstrap::icon_spacer();
                }
                $displayname  = $icon . ' ' . $plugin->displayname;
                $displayname = new html_table_cell($displayname);

                $rootdir = new html_table_cell($plugin->get_dir());

                if ($isstandard = $plugin->is_standard()) {
                    $row->attributes['class'] .= ' standard';
                    $source = new html_table_cell(get_string('sourcestd', 'core_plugin'));
                } else {
                    $row->attributes['class'] .= ' extension';
                    $source = new html_table_cell(get_string('sourceext', 'core_plugin'));
                }

                $versiondb = new html_table_cell($plugin->versiondb);
                $versiondisk = new html_table_cell($plugin->versiondisk);

                $statuscode = $plugin->get_status();
                $row->attributes['class'] .= ' status-' . $statuscode;
                $status = get_string('status_' . $statuscode, 'core_plugin');

                $availableupdates = $plugin->available_updates();
                if (!empty($availableupdates) and empty($CFG->disableupdatenotifications)) {
                    foreach ($availableupdates as $availableupdate) {
                        $status .= $this->plugin_available_update_info($availableupdate);
                    }
                }

                $status = new html_table_cell($status);

                $requires = new html_table_cell($this->required_column($plugin, $pluginman, $version));

                $statusisboring = in_array($statuscode, array(
                        plugin_manager::PLUGIN_STATUS_NODB, plugin_manager::PLUGIN_STATUS_UPTODATE));

                $coredependency = $plugin->is_core_dependency_satisfied($version);
                $otherpluginsdependencies = $pluginman->are_dependencies_satisfied($plugin->get_other_required_plugins());
                $dependenciesok = $coredependency && $otherpluginsdependencies;

                if ($options['xdep']) {
                    // we want to see only plugins with failed dependencies
                    if ($dependenciesok) {
                        continue;
                    }

                } else if ($isstandard and $statusisboring and $dependenciesok and empty($availableupdates)) {
                    // no change is going to happen to the plugin - display it only
                    // if the user wants to see the full list
                    if (empty($options['full'])) {
                        continue;
                    }
                }

                // ok, the plugin should be displayed
                $numofhighlighted[$type]++;

                $row->cells = array($displayname, $rootdir, $source,
                    $versiondb, $versiondisk, $requires, $status);
                $plugintyperows[] = $row;
            }

            if (empty($numofhighlighted[$type]) and empty($options['full'])) {
                continue;
            }

            $table->data[] = $header;
            $table->data = array_merge($table->data, $plugintyperows);
        }

        $sumofhighlighted = array_sum($numofhighlighted);

        if ($options['xdep']) {
            // we do not want to display no heading and links in this mode
            $out = '';

        } else if ($sumofhighlighted == 0) {
            $out  = $this->output->container_start('nonehighlighted', 'plugins-check-info');
            $out .= $this->output->heading(get_string('nonehighlighted', 'core_plugin'));
            if (empty($options['full'])) {
                $out .= html_writer::link(new moodle_url('/admin/index.php',
                    array('confirmupgrade' => 1, 'confirmrelease' => 1, 'showallplugins' => 1)),
                    get_string('nonehighlightedinfo', 'core_plugin'));
            }
            $out .= $this->output->container_end();

        } else {
            $out  = $this->output->container_start('somehighlighted', 'plugins-check-info');
            $out .= $this->output->heading(get_string('somehighlighted', 'core_plugin', $sumofhighlighted));
            if (empty($options['full'])) {
                $out .= html_writer::link(new moodle_url('/admin/index.php',
                    array('confirmupgrade' => 1, 'confirmrelease' => 1, 'showallplugins' => 1)),
                    get_string('somehighlightedinfo', 'core_plugin'));
            } else {
                $out .= html_writer::link(new moodle_url('/admin/index.php',
                    array('confirmupgrade' => 1, 'confirmrelease' => 1, 'showallplugins' => 0)),
                    get_string('somehighlightedonly', 'core_plugin'));
            }
            $out .= $this->output->container_end();
        }

        if ($sumofhighlighted > 0 or $options['full']) {
            $out .= html_writer::table($table);
        }

        return $out;
    }

    /**
     * Formats the information that needs to go in the 'Requires' column.
     * @param plugininfo_base $plugin the plugin we are rendering the row for.
     * @param plugin_manager $pluginman provides data on all the plugins.
     * @param string $version
     * @return string HTML code
     */
    protected function required_column(plugininfo_base $plugin, plugin_manager $pluginman, $version) {
        $requires = array();

        if (!empty($plugin->versionrequires)) {
            if ($plugin->versionrequires <= $version) {
                $class = 'requires-ok';
            } else {
                $class = 'requires-failed';
            }
            $requires[] = html_writer::tag('li',
                get_string('moodleversion', 'core_plugin', $plugin->versionrequires),
                array('class' => $class));
        }

        foreach ($plugin->get_other_required_plugins() as $component => $requiredversion) {
            $ok = true;
            $otherplugin = $pluginman->get_plugin_info($component);

            if (is_null($otherplugin)) {
                $ok = false;
            } else if ($requiredversion != ANY_VERSION and $otherplugin->versiondisk < $requiredversion) {
                $ok = false;
            }

            if ($ok) {
                $class = 'requires-ok';
            } else {
                $class = 'requires-failed';
            }

            if ($requiredversion != ANY_VERSION) {
                $str = 'otherpluginversion';
            } else {
                $str = 'otherplugin';
            }
            $requires[] = html_writer::tag('li',
                    get_string($str, 'core_plugin',
                            array('component' => $component, 'version' => $requiredversion)),
                    array('class' => $class));
        }

        if (!$requires) {
            return '';
        }
        return html_writer::tag('ul', implode("\n", $requires));
    }

    /**
     * Prints an overview about the plugins - number of installed, number of extensions etc.
     *
     * @param plugin_manager $pluginman provides information about the plugins
     * @return string as usually
     */
    public function plugins_overview_panel(plugin_manager $pluginman) {
        global $CFG;

        $plugininfo = $pluginman->get_plugins();

        $numtotal = $numdisabled = $numextension = $numupdatable = 0;

        foreach ($plugininfo as $type => $plugins) {
            foreach ($plugins as $name => $plugin) {
                if ($plugin->get_status() === plugin_manager::PLUGIN_STATUS_MISSING) {
                    continue;
                }
                $numtotal++;
                if ($plugin->is_enabled() === false) {
                    $numdisabled++;
                }
                if (!$plugin->is_standard()) {
                    $numextension++;
                }
                if (empty($CFG->disableupdatenotifications) and $plugin->available_updates()) {
                    $numupdatable++;
                }
            }
        }

        $info = array();
        $info[] = html_writer::tag('span', get_string('numtotal', 'core_plugin', $numtotal), array('class' => 'info total'));
        $info[] = html_writer::tag('span', get_string('numdisabled', 'core_plugin', $numdisabled), array('class' => 'info disabled'));
        $info[] = html_writer::tag('span', get_string('numextension', 'core_plugin', $numextension), array('class' => 'info extension'));
        if ($numupdatable > 0) {
            $info[] = html_writer::tag('span', get_string('numupdatable', 'core_plugin', $numupdatable), array('class' => 'info updatable'));
        }

        return $this->output->box(implode(html_writer::tag('span', ' ', array('class' => 'separator')), $info), '', 'plugins-overview-panel');
    }

    /**
     * Displays all known plugins and links to manage them
     *
     * This default implementation renders all plugins into one big table.
     *
     * @param plugin_manager $pluginman provides information about the plugins.
     * @return string HTML code
     */
    public function plugins_control_panel(plugin_manager $pluginman) {
        global $CFG;

        $plugininfo = $pluginman->get_plugins();

        if (empty($plugininfo)) {
            return '';
        }

        $table = new html_table();
        $table->id = 'plugins-control-panel';
        $table->head = array(
            get_string('displayname', 'core_plugin'),
            get_string('source', 'core_plugin'),
            get_string('version', 'core_plugin'),
            get_string('availability', 'core_plugin'),
            get_string('actions', 'core_plugin'),
            get_string('notes','core_plugin'),
        );
        $table->colclasses = array(
            'pluginname', 'source', 'version', 'availability', 'actions', 'notes'
        );

        foreach ($plugininfo as $type => $plugins) {

            $header = new html_table_cell($pluginman->plugintype_name_plural($type));
            $header->header = true;
            $header->colspan = count($table->head);
            $header = new html_table_row(array($header));
            $header->attributes['class'] = 'plugintypeheader type-' . $type;
            $table->data[] = $header;

            if (empty($plugins)) {
                $msg = new html_table_cell(get_string('noneinstalled', 'core_plugin'));
                $msg->colspan = count($table->head);
                $row = new html_table_row(array($msg));
                $row->attributes['class'] .= 'msg msg-noneinstalled';
                $table->data[] = $row;
                continue;
            }

            foreach ($plugins as $name => $plugin) {
                $row = new html_table_row();
                $row->attributes['class'] = 'type-' . $plugin->type . ' name-' . $plugin->type . '_' . $plugin->name;

                if ($this->page->theme->resolve_image_location('icon', $plugin->type . '_' . $plugin->name)) {
                    $icon = $this->output->pix_icon('icon', '', $plugin->type . '_' . $plugin->name, array('class' => 'smallicon pluginicon'));
                } else {
                    $icon = $this->output->pix_icon('spacer', '', 'moodle', array('class' => 'smallicon pluginicon noicon'));
                }
                if ($plugin->get_status() === plugin_manager::PLUGIN_STATUS_MISSING) {
                    $msg = html_writer::tag('span', get_string('status_missing', 'core_plugin'), array('class' => 'notifyproblem'));
                    $row->attributes['class'] .= ' missingfromdisk';
                } else {
                    $msg = '';
                }
                $pluginname  = html_writer::tag('div', $icon . ' ' . $plugin->displayname . ' ' . $msg, array('class' => 'displayname')).
                               html_writer::tag('div', $plugin->component, array('class' => 'componentname'));
                $pluginname  = new html_table_cell($pluginname);

                if ($plugin->is_standard()) {
                    $row->attributes['class'] .= ' standard';
                    $source = new html_table_cell(get_string('sourcestd', 'core_plugin'));
                } else {
                    $row->attributes['class'] .= ' extension';
                    $source = new html_table_cell(get_string('sourceext', 'core_plugin'));
                }

                $version = new html_table_cell($plugin->versiondb);

                $isenabled = $plugin->is_enabled();
                if (is_null($isenabled)) {
                    $availability = new html_table_cell('');
                } else if ($isenabled) {
                    $row->attributes['class'] .= ' enabled';
                    $icon = $this->output->pix_icon('i/hide', get_string('pluginenabled', 'core_plugin'));
                    $availability = new html_table_cell($icon . ' ' . get_string('pluginenabled', 'core_plugin'));
                } else {
                    $row->attributes['class'] .= ' disabled';
                    $icon = $this->output->pix_icon('i/show', get_string('plugindisabled', 'core_plugin'));
                    $availability = new html_table_cell($icon . ' ' . get_string('plugindisabled', 'core_plugin'));
                }

                $actions = array();

                $settingsurl = $plugin->get_settings_url();
                if (!is_null($settingsurl)) {
                    $actions[] = html_writer::link($settingsurl, get_string('settings', 'core_plugin'), array('class' => 'settings'));
                }

                $uninstallurl = $plugin->get_uninstall_url();
                if (!is_null($uninstallurl)) {
                    $actions[] = html_writer::link($uninstallurl, get_string('uninstall', 'core_plugin'), array('class' => 'uninstall'));
                }

                $actions = new html_table_cell(implode(html_writer::tag('span', ' ', array('class' => 'separator')), $actions));

                $requriedby = $pluginman->other_plugins_that_require($plugin->component);
                if ($requriedby) {
                    $requiredby = html_writer::tag('div', get_string('requiredby', 'core_plugin', implode(', ', $requriedby)),
                        array('class' => 'requiredby'));
                } else {
                    $requiredby = '';
                }

                $updateinfo = '';
                if (empty($CFG->disableupdatenotifications) and is_array($plugin->available_updates())) {
                    foreach ($plugin->available_updates() as $availableupdate) {
                        $updateinfo .= $this->plugin_available_update_info($availableupdate);
                    }
                }

                $notes = new html_table_cell($requiredby.$updateinfo);

                $row->cells = array(
                    $pluginname, $source, $version, $availability, $actions, $notes
                );
                $table->data[] = $row;
            }
        }

        return html_writer::table($table);
    }

    /**
     * Helper method to render the information about the available plugin update
     *
     * The passed objects always provides at least the 'version' property containing
     * the (higher) version of the plugin available.
     *
     * @param available_update_info $updateinfo information about the available update for the plugin
     */
    protected function plugin_available_update_info(available_update_info $updateinfo) {

        $boxclasses = 'pluginupdateinfo';
        $info = array();

        if (isset($updateinfo->release)) {
            $info[] = html_writer::tag('span', get_string('updateavailable_release', 'core_plugin', $updateinfo->release),
                array('class' => 'info release'));
        }

        if (isset($updateinfo->maturity)) {
            $info[] = html_writer::tag('span', get_string('maturity'.$updateinfo->maturity, 'core_admin'),
                array('class' => 'info maturity'));
            $boxclasses .= ' maturity'.$updateinfo->maturity;
        }

        if (isset($updateinfo->download)) {
            $info[] = html_writer::link($updateinfo->download, get_string('download'), array('class' => 'info download'));
        }

        if (isset($updateinfo->url)) {
            $info[] = html_writer::link($updateinfo->url, get_string('updateavailable_moreinfo', 'core_plugin'),
                array('class' => 'info more'));
        }

        $box  = $this->output->box_start($boxclasses);
        $box .= html_writer::tag('div', get_string('updateavailable', 'core_plugin', $updateinfo->version), array('class' => 'version'));
        $box .= $this->output->box(implode(html_writer::tag('span', ' ', array('class' => 'separator')), $info), '');
        $box .= $this->output->box_end();

        return $box;
    }

    /**
     * This function will render one beautiful table with all the environmental
     * configuration and how it suits Moodle needs.
     *
     * @param boolean $result final result of the check (true/false)
     * @param array $environment_results array of results gathered
     * @return string HTML to output.
     */
    public function environment_check_table($result, $environment_results) {
        global $CFG;

        // Table headers
        $servertable = new html_table();//table for server checks
        $servertable->head  = array(
            get_string('name'),
            get_string('info'),
            get_string('report'),
            get_string('status'),
        );
        $servertable->wrap  = array('nowrap', '', '', 'nowrap');
        $servertable->attributes['class'] = 'table environmenttable generaltable';

        $serverdata = array('ok'=>array(), 'warn'=>array(), 'error'=>array());

        $othertable = new html_table();//table for custom checks
        $othertable->head  = array(
            get_string('info'),
            get_string('report'),
            get_string('status'),
        );
        $othertable->wrap  = array('', '', 'nowrap');
        $othertable->attributes['class'] = 'table environmenttable generaltable';

        $otherdata = array('ok'=>array(), 'warn'=>array(), 'error'=>array());

        // Iterate over each environment_result
        $continue = true;
        foreach ($environment_results as $environment_result) {
            $errorline   = false;
            $warningline = false;
            $stringtouse = '';
            if ($continue) {
                $type = $environment_result->getPart();
                $info = $environment_result->getInfo();
                $status = $environment_result->getStatus();
                $error_code = $environment_result->getErrorCode();
                // Process Report field
                $rec = new stdClass();
                // Something has gone wrong at parsing time
                if ($error_code) {
                    $stringtouse = 'environmentxmlerror';
                    $rec->error_code = $error_code;
                    $status = get_string('error');
                    $errorline = true;
                    $continue = false;
                }

                if ($continue) {
                    if ($rec->needed = $environment_result->getNeededVersion()) {
                        // We are comparing versions
                        $rec->current = $environment_result->getCurrentVersion();
                        if ($environment_result->getLevel() == 'required') {
                            $stringtouse = 'environmentrequireversion';
                        } else {
                            $stringtouse = 'environmentrecommendversion';
                        }

                    } else if ($environment_result->getPart() == 'custom_check') {
                        // We are checking installed & enabled things
                        if ($environment_result->getLevel() == 'required') {
                            $stringtouse = 'environmentrequirecustomcheck';
                        } else {
                            $stringtouse = 'environmentrecommendcustomcheck';
                        }

                    } else if ($environment_result->getPart() == 'php_setting') {
                        if ($status) {
                            $stringtouse = 'environmentsettingok';
                        } else if ($environment_result->getLevel() == 'required') {
                            $stringtouse = 'environmentmustfixsetting';
                        } else {
                            $stringtouse = 'environmentshouldfixsetting';
                        }

                    } else {
                        if ($environment_result->getLevel() == 'required') {
                            $stringtouse = 'environmentrequireinstall';
                        } else {
                            $stringtouse = 'environmentrecommendinstall';
                        }
                    }

                    // Calculate the status value
                    if ($environment_result->getBypassStr() != '') {            //Handle bypassed result (warning)
                        $status = get_string('bypassed');
                        $warningline = true;
                    } else if ($environment_result->getRestrictStr() != '') {   //Handle restricted result (error)
                        $status = get_string('restricted');
                        $errorline = true;
                    } else {
                        if ($status) {                                          //Handle ok result (ok)
                            $status = get_string('ok');
                        } else {
                            if ($environment_result->getLevel() == 'optional') {//Handle check result (warning)
                                $status = get_string('check');
                                $warningline = true;
                            } else {                                            //Handle error result (error)
                                $status = get_string('check');
                                $errorline = true;
                            }
                        }
                    }
                }

                // Build the text
                $linkparts = array();
                $linkparts[] = 'admin/environment';
                $linkparts[] = $type;
                if (!empty($info)){
                   $linkparts[] = $info;
                }
                if (empty($CFG->docroot)) {
                    $report = get_string($stringtouse, 'admin', $rec);
                } else {
                    $report = $this->doc_link(join($linkparts, '/'), get_string($stringtouse, 'admin', $rec));
                }

                // Format error or warning line
                if ($errorline || $warningline) {
                    $messagetype = $errorline? 'important':'warning';
                } else {
                    $messagetype = 'success';
                }
                $status = '<span class="label label-'.$messagetype.'">'.$status.'</span>';
                // Here we'll store all the feedback found
                $feedbacktext = '';
                // Append the feedback if there is some
                $feedbacktext .= $environment_result->strToReport($environment_result->getFeedbackStr(), 'alert alert-'.$messagetype);
                //Append the bypass if there is some
                $feedbacktext .= $environment_result->strToReport($environment_result->getBypassStr(), 'alert');
                //Append the restrict if there is some
                $feedbacktext .= $environment_result->strToReport($environment_result->getRestrictStr(), 'alert alert-error');

                $report .= $feedbacktext;

                // Add the row to the table
                if ($environment_result->getPart() == 'custom_check'){
                    $otherdata[$messagetype][] = array ($info, $report, $status);
                } else {
                    $serverdata[$messagetype][] = array ($type, $info, $report, $status);
                }
            }
        }

        //put errors first in
        $servertable->data = array_merge($serverdata['important'], $serverdata['warning'], $serverdata['success']);
        $othertable->data = array_merge($otherdata['important'], $otherdata['warning'], $otherdata['success']);

        // Print table
        $output = '';
        $output .= $this->heading(get_string('serverchecks', 'admin'));
        $output .= html_writer::table($servertable);
        if (count($othertable->data)){
            $output .= $this->heading(get_string('customcheck', 'admin'));
            $output .= html_writer::table($othertable);
        }

        // Finally, if any error has happened, print the summary box
        if (!$result) {
            $output .= $this->box(get_string('environmenterrortodo', 'admin'), 'alert alert-error');
        }

        return $output;
    }
}
