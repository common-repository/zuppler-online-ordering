<div class="inner-sidebar">
  <div class="postbox">
    <h3>HELP - Plugin Usage</h3>
    <div class="inside" style="font-size: 11px;margin: 6px 6px 8px;">
      <p>To display the Zuppler Menu, add the following shortcode to your post or page:</p>
      <div class="info">
        <strong>[zuppler]</strong>
      </div>
      <em>Using the shortcode without parameters will render the menu using the default options configured in this page.</em>

      <p>It is possible to render a different restaurant menu or to customize features by providing different attributes to your shortcode. <br />Here are some common use cases:</p>
      
      <div class="info">
        <strong>[zuppler restaurant="location2" id="###"]</strong>
      </div>
      <em>Renders a different restaurant menu. Note that location2 must be a valid restaurant added to your channel by a Zuppler member. Both attributes are required in this case.</em>

      <p>The <strong>options</strong> param can be used to customize different features:</p>
      <div class="info">
        <strong>[zuppler options=" data-item='plus' " ... ]</strong>
      </div>
      <em>Renders the item modifiers as a list of pictures. It falls back to standard version if case there are no pictures configured.</em>

      <p>You can also combine options by passing multiple data attributes:</p>
      <div class="info">
        <strong>[zuppler options=" data-feature1='A' data-feature2='B' " ... ]</strong>
      </div>


      <p>For more information about the Zuppler API please visit <a href="http://api.zuppler.com/docs" target="_blank">Zuppler documentation</a>.</p>
    </div>
  </div>
</div>