<?php
/**
* @version    $Id: swap_vmassets.php $
* @package    Joomla Extensions
* @copyright  Copyright (C) 2006 - 2011 David Beuving All rights reserved.
* @license    GNU/GPL v2 or later
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class  plgContentK2_rdownloads extends JPlugin {

  function onAfterDispatch() {
    
    $doc  = JFactory::getDocument();
    $app  = JFactory::getApplication();
    $user =& JFactory::getUser();
    
    if (!$app->isAdmin()) {
      $on = $this->params->get('enabled', 0);
      if ($on) {
        //$doc->addScriptDeclaration( $content );
        $content = <<<script
/*!
 * jQuery Cookie Plugin v1.3
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2011, Klaus Hartl
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.opensource.org/licenses/GPL-2.0
 */
(function ($, document, undefined) {

  var pluses = /\+/g;

  function raw(s) {
    return s;
  }

  function decoded(s) {
    return decodeURIComponent(s.replace(pluses, ' '));
  }

  var config = $.cookie = function (key, value, options) {

    // write
    if (value !== undefined) {
      options = $.extend({}, config.defaults, options);

      if (value === null) {
        options.expires = -1;
      }

      if (typeof options.expires === 'number') {
        var days = options.expires, t = options.expires = new Date();
        t.setDate(t.getDate() + days);
      }

      value = config.json ? JSON.stringify(value) : String(value);

      return (document.cookie = [
        encodeURIComponent(key), '=', config.raw ? value : encodeURIComponent(value),
        options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
        options.path    ? '; path=' + options.path : '',
        options.domain  ? '; domain=' + options.domain : '',
        options.secure  ? '; secure' : ''
      ].join(''));
    }

    // read
    var decode = config.raw ? raw : decoded;
    var cookies = document.cookie.split('; ');
    for (var i = 0, l = cookies.length; i < l; i++) {
      var parts = cookies[i].split('=');
      if (decode(parts.shift()) === key) {
        var cookie = decode(parts.join('='));
        return config.json ? JSON.parse(cookie) : cookie;
      }
    }

    return null;
  };

  config.defaults = {};

  $.removeCookie = function (key, options) {
    if ($.cookie(key) !== null) {
      $.cookie(key, null, options);
      return true;
    }
    return false;
  };

})(jQuery, document);
var rd = jQuery.noConflict();

rd(document).ready(function(){
  var reg_url = '/index.php?option=com_users&view=registration';
  var log_url = '/index.php?option=com_users';
  var text = 'You must be <a href="'+reg_url+'">Registered</a> and <a href="'+log_url+'">Logged in</a> to Download';
  rd('.itemAttachmentsBlock').html(text);
  
  var cookie = rd.cookie('secure_session');

  
});
script;

        if ($user->id < 1) {
          $doc->addScriptDeclaration( $content );
        }
      }
    }
  }
}

