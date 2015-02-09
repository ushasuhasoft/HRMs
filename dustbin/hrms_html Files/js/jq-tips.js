/* ver 0.1.0
 * 2014-07-12
 * by: Carlos A. Gomes
 */
(function($) {
  var cacheElms = [];
  var nTips = 0;
  var $body;

  $.fn.jqTips = function(params) {
    var options;
    $body = $body || $('body');

    var defaults = {
      delay: 100
    };

    options = $.extend(true, defaults, params);

    /**
     * Return a cached element or create a new one.
     *
     * @param {$} obj
     * @returns {$}
     */
    function getElm(obj) {
      var ret = false;

      ret = cacheElms[obj.attr("id")] || createNewTip(obj);

      return ret;
    }

    /**
     *
     * @param {$} obj
     * @returns {$}
     */
    function createNewTip( obj ) {
      var id    = obj.attr("id");
      var title = obj.attr("title");
      var ret   = $("<div />");

      obj.removeAttr("title");

      ret.fadeTo(0);
      ret.attr("id", ("jq-tips_" + id));
      ret.addClass("jq-tips_tip");

      var content = $("<div />");
      content.addClass("jq-tips_content");
      content.html(title);
      content.appendTo(ret);

      var pointer = $('<i />');
      pointer.addClass('jq-tips_pointer');
      pointer.appendTo(ret);

      $body.append(ret);

      cacheElms[id] = ret;

      return ret;
    }

    var HoverIn = function() {
      var obj     = $(this);
      var offset  = obj.offset();
      var toolTip = getElm(obj);

      var Css = {
        top     : (offset.top - toolTip.height() - 10),
        left    : (offset.left - (toolTip.width() * 0.5) + (obj.width() / 2)),
        display : "block"
      };

      toolTip.css(Css);

      toolTip.animate(
        {
          opacity   : 1
        },
        {
          duration  : options.delay,
          queue     : false
        }
      );
    };

    var HoverOut = function() {
      var toolTip = getElm($(this));

      toolTip.clearQueue().removeAttr("style").animate(
        {
          opacity : 0
        },
        100,
        function() {
          toolTip.css({
            display : "none",
            top     : "-10000px",
            left    : "-10000px"
          });
        });
    };

    var Handdler = function() {
      var obj = $(this);

      var id = obj.attr("id");

      if( !id ) {
        nTips += 1;

        id = "jq-tips_activator-" + (nTips);
        obj.attr("id", id);
      }

      obj.hover(HoverIn, HoverOut);

    };

    return this.each(Handdler);
  };

})(jQuery);

