<?php

wp_enqueue_script('wp-color-picker');
wp_enqueue_style( 'wp-color-picker' );

$default_listing_template = '{{#restaurants}}
<div class="restaurant is_restaurant_open" id="restaurant_{{id}}">
  <h1>{{name}}</h1>
  {{#restaurant_logo}}
    <img src="{{thumb}}" /><br />
  {{/restaurant_logo}}
  <p><strong>Locations:</strong>
    {{#locations}} <br />{{name}} {{/locations}}
  </p>
  <p><strong>Cuisines:</strong> {{cuisines}}</p>
  <p><strong>Services:</strong> {{amenities}}</p>
  <p><strong>Hours of Operation:</strong><br>{{& hours_of_operation.info}}</p>
  <p>The restaurant is <strong class="is_restaurant_open"></strong> now.</p>
  <a href="order-online?restaurant={{permalink}}">Order Online</a>
</div>
{{/restaurants}}';

$default_locale="en";

$default_colors_background='#ffffff';
$default_colors_highContrast='#000000';
$default_colors_midContrast='#333333';
$default_colors_lowContrast='#999999';
$default_colors_brand='#0070e1';
$default_colors_heroBackground='#9e5375';
$default_colors_heroContrast='#FFFFFF';
$default_fonts_heading="Open Sans Condensed, helvetica, sans-serif";
$default_fonts_body="Raleway, helvetica, sans-serif";
$default_fonts_deco="Montserrat, helvetica, sans-serif";

$options = array (

  array( "type" => "open", "name" => "Integration Options" ),
  array(
    "name" => "Integration Type",
    "id" => $shortname."_integration_type",
    "type" => "radio",
    "std" => "0",
    "options" => array(
      array("value" => "0", "label" => "Single Restaurant"),
      array("value" => "1", "label" => "Portal"),
    ) ),

  array("type" => "close"),
  array( "type" => "open", "name" => "Restaurant Options" ),
  
  array(
    "name" => "Channel Slug",
    "desc"  => "Provided by Zuppler Staff. E.g: <strong>demorestaurant</strong>",
    "id" => $shortname."_channel_slug",
    "type" => "text" ),
  array(
    "name" => "Locale",
    "desc"  => "E.g: <strong>en</strong>, <strong>en-IE</strong>, etc",
    "id" => $shortname."_locale",
    "std" => $default_locale,
    "type" => "text" ),
  array(
    "name" => "Restaurant Slug",
    "desc"  => "Provided by Zuppler Staff. E.g: <strong>demorestaurant</strong>",
    "id" => $shortname."_restaurant_slug",
    "type" => "text" ),
  array(
    "name" => "Restaurant ID",
    "desc"  => "<a href='#' id='get_restaurant_id'>get restaurant id</a> <span class='zspinner'></span><br /><span id='get_restaurant_id_info'></span>",
    "id" => $shortname."_restaurant_id",
    "type" => "text" ),
  
    array("type" => "close"),
  array( "type" => "open", "name" => "Colors" ),

  array(
    "name" => "Background",
    "desc"  => "Usually the same as your template background color",
    "id" => $shortname."_colors_background",
    "std" => $default_colors_background,
    "type" => "color" ),
  array(
    "name" => "High Contrast",
    "id" => $shortname."_colors_highContrast",
    "std" => $default_colors_highContrast,
    "type" => "color" ),
  array(
    "name" => "Mid Contrast",
    "id" => $shortname."_colors_midContrast",
    "std" => $default_colors_midContrast,
    "type" => "color" ),
  array(
    "name" => "Low Contrast",
    "id" => $shortname."_colors_lowContrast",
    "std" => $default_colors_lowContrast,
    "type" => "color" ),
  array(
    "name" => "Brand",
    "id" => $shortname."_colors_brand",
    "std" => $default_colors_brand,
    "type" => "color" ),
  array(
    "name" => "Hero Background",
    "desc"  => "Mask background color for categories that has an image associated",
    "id" => $shortname."_colors_heroBackground",
    "std" => $default_colors_heroBackground,
    "type" => "color" ),
  array(
    "name" => "Hero Contrast",
    "desc"  => "for categories that has an image associated",
    "id" => $shortname."_colors_heroContrast",
    "std" => $default_colors_heroContrast,
    "type" => "color" ),

  array("type" => "close"),
  array( "type" => "open", "name" => "Fonts" ),
  
  array(
    "name" => "Headings Font",
    "id" => $shortname."_fonts_heading",
    "std" => $default_fonts_heading,
    "type" => "text" ),
  array(
    "name" => "Body Font",
    "id" => $shortname."_fonts_body",
    "std" => $default_fonts_body,
    "type" => "text" ),
  array(
    "name" => "Deco Font",
    "id" => $shortname."_fonts_deco",
    "std" => $default_fonts_deco,
    "type" => "text" ),

  array("type" => "close")

); // $options



if ( 'save' == @$_POST['action'] ) {
  // if ( get_magic_quotes_gpc() ) {
  //   $_POST      = array_map( 'stripslashes_deep', $_POST );
  //   $_REQUEST   = array_map( 'stripslashes_deep', $_REQUEST );
  // }

  foreach ($options as $value) {
    if( isset($value['id']) ) {
      if( isset( $_POST[ $value['id'] ] ) ) {
        update_option( $value['id'], htmlentities(stripslashes($_POST[ $value['id'] ] ), ENT_QUOTES)  );
      } else {
        delete_option( $value['id'] );
      }
    }
  }

  ?><div class="updated"><p><strong><?php _e('Options saved.'); ?></strong></p></div><?php
} else if( 'reset' == @$_POST['action'] ) {
  foreach ($options as $value) {
    if( isset($value['id']) ) {
      // print_r($value);
      if(empty($value['std'])) delete_option( $value['id'] );
      else update_option( $value['id'], htmlentities(stripslashes($value['std']), ENT_QUOTES)  );
    }
  }
}

?>

<h3>Zuppler Online Ordering Options</h3>

<div class="has-right-sidebar meta-box-sortables zuppler-options">
  <?php include("content-options-help.php"); ?>
  
  <div id="post-body">
    <div id="post-body-content">
      <form name="zuppler_form" id="zuppler_form" method="post" action="<?php echo $form_action; ?>">

<?php
foreach ($options as $value) {
  if( isset($value['id']) ) {
    $stored_value = html_entity_decode(get_option( $value['id'] ));
  }
  switch ( $value['type'] ) {

    case "open": ?>

        <div class="postbox">
          <div class="handlediv" title="Click to toggle"><br></div>
          <h3 class="hndle"><?php echo $value['name']; ?></h3>
          <div class="inside">
          <table class="form-table">

    <?php break;

    case "close": ?>

          </table>
          </div>
        </div>

    <?php break;

    case 'text': ?>

      <tr id="<?php echo $value['id']; ?>_row">
        <th scope="row"><?php echo $value['name']; ?></th>
        <td>
          <input type="text" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="<?php if ( $stored_value != "") { echo $stored_value; } else if(isset($value['std'])) { echo $value['std']; } ?>" class="regular-text" />
          <?php if (!empty($value['desc'])) { ?><br /><span class="description"><?php echo $value['desc']; ?></span><?php } ;?>
        </td>
      </tr>

    <?php break;

    case 'color': ?>

      <tr id="<?php echo $value['id']; ?>_row">
        <th scope="row"><?php echo $value['name']; ?></th>
        <td>
          <input type="text" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="<?php if ( $stored_value != "") { echo $stored_value; } else if(isset($value['std'])) { echo $value['std']; } ?>" style="height: 30px" class="wp-color-picker" />
          <?php if (!empty($value['desc'])) { ?><br /><span class="description"><?php echo $value['desc']; ?></span><?php } ;?>
        </td>
      </tr>

    <?php break;

    case 'separator': ?>

      <tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr>
      <tr><td colspan="2">&nbsp;</td></tr>

    <?php break;

    case 'textarea': ?>

      <tr id="<?php echo $value['id']; ?>_row">
        <td colspan="2">
          <label><?php echo $value['name']; ?></label>
          <textarea name="<?php echo $value['id']; ?>" style="width:99%; height:300px;" class="z-code-editor"><?php if ( $stored_value != "") { echo $stored_value; } else { echo $value['std']; } ?></textarea>
            <?php if (!empty($value['desc'])) { ?>
            <div style="clear:both;">
              <span class="description"><?php echo $value['desc']; ?></span>
            </div>
            <?php } ;?>
        </td>
      </tr>

    <?php break;

    case 'select': ?>

      <tr id="<?php echo $value['id']; ?>_row">
        <th scope="row"><?php echo $value['name']; ?></th>
        <td>
          <?php
            $selected = ( $stored_value != "" ) ? $stored_value : $value["std"];
          ?>
          <select style="width:240px;" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
            <?php foreach ($value['options'] as $option) { ?>
              <option value="<?php echo $option['value']; ?>" <?php if ( $selected == $option['value']) { echo ' selected="selected"'; } ?>>
                <?php echo $option['label']; ?>
              </option>
            <?php } ?>
          </select>
          <?php if (!empty($value['desc'])) { ?><br /><span class="description"><?php echo $value['desc']; ?></span><?php } ;?>
        </td>
      </tr>

    <?php break;

    case 'radio': ?>

      <tr id="<?php echo $value['id']; ?>_row">
        <?php if(isset($value['appearence']) && $value['appearence'] == 'large') { ?>
          <td colspan="2">
            <label><?php echo $value['name']; ?></label><br /><br />
        <?php } else { ?>
          <th scope="row"><?php echo $value['name']; ?></th>
          <td>
        <?php } ?>
          <?php $selected = ( $stored_value != "" ) ? $stored_value : $value["std"]; ?>
          <?php foreach ($value['options'] as $option) { ?>
            <label>
              <input type="radio" name="<?php echo $value['id']; ?>" value="<?php echo $option['value']; ?>" <?php if ( $selected == $option['value']) { echo ' checked="checked"'; } ?>/>
              <?php echo $option['label']; ?>
            </label>
          <?php } ?>

          <?php if (!empty($value['desc'])) { ?><br /><span class="description"><?php echo $value['desc']; ?></span><?php } ;?>
        </td>
      </tr>

    <?php break;

    case "checkbox": ?>

      <tr id="<?php echo $value['id']; ?>_row">
        <th scope="row"><?php echo $value['name']; ?></th>
        <td><?php if($stored_value){ $checked = "checked=\"checked\""; }else{ $checked = ""; } ?>
          <input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
          <?php if (!empty($value['desc'])) { ?><br /><span class="description"><?php echo $value['desc']; ?></span><?php } ;?>
        </td>
      </tr>

     <?php break;
  }
}
?>

        <p class="submit">
          <input type="submit" name="Submit" value="<?php _e('Update Options', 'zuppler_tr' ) ?>" class="button-primary" />
          <input name="reset" type="submit" id="reset_template_button" value="reset" />
          <input type="hidden" name="action" value="save" id="zuppler_form_action" />
        </p>
      </form>
    </div>
  </div>

  <style>
    .zspinner {
      background: url('/wp-admin/images/wpspin_light.gif') no-repeat;
      background-size: 16px 16px;
      display: none;
      position:absolute;
      opacity: .7;
      width: 16px;height: 16px;
      margin: 5px 5px 0;}
    .zspinner.active {
      display: inline-block;
    }
    #get_restaurant_id_info {color: red;}
  </style>


<script type="text/javascript" charset="utf-8">
  jQuery(document).ready( function($) {
    $('.wp-color-picker').wpColorPicker();

    $('.postbox h3, .postbox .handlediv, .stuffbox h3').click( function() {
      $(this).parent().toggleClass('closed');
    });

    $('#reset_template_button').click(function(e){
      if(confirm("Do you really want to reset your options?")) {
        $('#zuppler_form_action').val('reset');
        $('#zuppler_form').submit();
      }
      e.preventDefault();
    });

    $('#zuppler_restaurant_id_row, #zuppler_restaurant_slug_row').toggle($("#zuppler_integration_type_row input[type='radio']").first().is(":checked"));
    $("#zuppler_integration_type_row input[type='radio']").change(function(){
      $('#zuppler_restaurant_id_row, #zuppler_restaurant_slug_row').toggle($("#zuppler_integration_type_row input[type='radio']").first().is(":checked"));
    });

    $('#zuppler_locale_row').toggle($("#zuppler_integration_type_row input[type='radio']").last().is(":checked"));
    $("#zuppler_integration_type_row input[type='radio']").change(function(){
      $('#zuppler_locale_row').toggle($("#zuppler_integration_type_row input[type='radio']").last().is(":checked"));
    });

    $('#get_restaurant_id').click(function(e){
      e.preventDefault();
      $info = $("#get_restaurant_id_info");
      $info.html("");
      restaurant = $('#zuppler_restaurant_slug').val();
      channel = $('#zuppler_channel_slug').val();

      if (!restaurant || !channel) {
        $("#zuppler_channel_slug_row, #zuppler_restaurant_slug_row").addClass("form-invalid");
        $info.html("Please fill in the Channel Slug & Restaurant Slug to get the restaurant id");
        return;
      }

      url = "//api.zuppler.com/v3/channels/"+channel+"/restaurants/"+restaurant+".json";
      $("#zuppler_restaurant_id_row .zspinner").addClass('active');

      $("#zuppler_channel_slug_row, #zuppler_restaurant_slug_row").removeClass("form-invalid");

      $.getJSON(url, function(data){
        $("#zuppler_restaurant_id_row .zspinner").removeClass('active');
        if (data.success) {
          $('#zuppler_restaurant_id').val(data.restaurant.id);
        } else {
          $("#zuppler_channel_slug_row, #zuppler_restaurant_slug_row").addClass("form-invalid");
          $info.html("Unable to get the restaurant id. Please make sure the Channel & Restaurant slugs are correct");
        }
      });
    });

  });
</script>
