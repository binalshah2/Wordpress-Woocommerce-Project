jQuery(document).ready(function($) {
  var admin_data = RNB_ADMIN_DATA;

  /**
   * Add new row
   *
   * @since 2.0.0
   * @return null
   */
  $('.add_redq_row').click(function() {
    $(this)
      .closest('table')
      .find('tbody')
      .append($(this).data('row'));
    $('body').trigger('row_added');
    return false;
  });

  /**
   * Cotnrol date format
   *
   * @since 2.0.0
   * @return null
   */
  var date_format;
  if (typeof admin_data.calendar_data != 'undefined') {
    if (admin_data.calendar_data.date_format.toLowerCase() === 'd/m/y') {
      date_format = 'd/m/yy';
    }

    if (admin_data.calendar_data.date_format.toLowerCase() === 'm/d/y') {
      date_format = 'm/d/yy';
    }

    if (admin_data.calendar_data.date_format.toLowerCase() === 'y/m/d') {
      date_format = 'yy/m/d';
    }
  }

  $('body').on('row_added', function() {
    $('.date-picker').datetimepicker({
      format: 'Y-m-d H:i:s',
    });
  });

  $('.date-picker').datetimepicker({
    format: 'Y-m-d H:i:s',
  });

  $('body').on('.add_redq_row', function() {
    $('.date-picker').datetimepicker({
      format: 'Y-m-d H:i:s',
    });
  });

  /**
   * Remove row
   *
   * @since 1.0.0
   * @version 2.0.0
   * @return null
   */
  $('body').on('click', 'button.remove_row', function() {
    $(this)
      .closest('.redq-remove-rows')
      .remove();

    // var inventoryCount = $('#rental_inventory_count').val(),
    //     incrementalCount = $('.rental_inventory').length;

    // if(parseInt(inventoryCount) <= parseInt(incrementalCount)){
    //    $('.add_redq_row').hide();
    // }else{
    //     $('.add_redq_row').show();
    // }

    return false;
  });

  // $('#rental_inventory_count').on('change' , function(){
  //     var inventoryCount = $(this).val(),
  //         incrementalCount = $('.rental_inventory').length;

  //     if(parseInt(inventoryCount) <= parseInt(incrementalCount)){
  //        $('.add_redq_row').hide();
  //     }else{
  //         $('.add_redq_row').show();
  //     }
  // });

  $('body').on('click', 'td.remove', function() {
    $(this)
      .closest('tr')
      .remove();
    return false;
  });

  $('body').on('click', 'td.inventory-remove', function() {
    var ids = JSON.parse($('.redq_availability_remove_id').val());

    var newId = $(this)
      .closest('tr')
      .data('id');
    ids.push(newId);

    $('.redq_availability_remove_id').val(JSON.stringify(ids));

    $(this)
      .closest('tr')
      .remove();
    return false;
  });

  /**
   * Show or hide row
   *
   * @since 2.0.0
   * @return null
   */
  $('.redq-hide-row').hide();

  $('body').on('click', '.show-or-hide', function(e) {
    $(this)
      .closest('div.redq-show-bar')
      .next('div.redq-hide-row')
      .slideToggle();
    return false;
  });

  $('.sortable').sortable({
    cursor: 'move',
  });

  /**
   * Control pricing types
   *
   * @since 2.0.0
   * @return null
   */
  $('.daily-pricing-panel').hide();
  $('.monthly-pricing-panel').hide();

  var pricingType = $('#pricing_type').val();

  if (pricingType == 'daily_pricing') {
    $('.daily-pricing-panel').show();
    $('.general-pricing-panel').hide();
    $('.monthly-pricing-panel').hide();
    $('.redq-days-range-panel').hide();
  } else if (pricingType == 'monthly_pricing') {
    $('.daily-pricing-panel').hide();
    $('.general-pricing-panel').hide();
    $('.monthly-pricing-panel').show();
    $('.redq-days-range-panel').hide();
  } else if (pricingType == 'days_range') {
    $('.daily-pricing-panel').hide();
    $('.general-pricing-panel').hide();
    $('.monthly-pricing-panel').hide();
    $('.redq-days-range-panel').show();
  } else if (pricingType == 'flat_hours') {
    $('.daily-pricing-panel').hide();
    $('.general-pricing-panel').hide();
    $('.monthly-pricing-panel').hide();
    $('.redq-days-range-panel').hide();
  } else {
    $('.daily-pricing-panel').hide();
    $('.general-pricing-panel').show();
    $('.monthly-pricing-panel').hide();
    $('.redq-days-range-panel').hide();
  }

  $('#pricing_type').change(function() {
    var pricingType = this.value;

    if (pricingType == 'daily_pricing') {
      $('.daily-pricing-panel').show();
      $('.general-pricing-panel').hide();
      $('.monthly-pricing-panel').hide();
      $('.redq-days-range-panel').hide();
    } else if (pricingType == 'monthly_pricing') {
      $('.daily-pricing-panel').hide();
      $('.general-pricing-panel').hide();
      $('.monthly-pricing-panel').show();
      $('.redq-days-range-panel').hide();
    } else if (pricingType == 'days_range') {
      $('.daily-pricing-panel').hide();
      $('.general-pricing-panel').hide();
      $('.monthly-pricing-panel').hide();
      $('.redq-days-range-panel').show();
    } else if (pricingType == 'flat_hours') {
      $('.daily-pricing-panel').hide();
      $('.general-pricing-panel').hide();
      $('.monthly-pricing-panel').hide();
      $('.redq-days-range-panel').hide();
    } else {
      $('.daily-pricing-panel').hide();
      $('.general-pricing-panel').show();
      $('.monthly-pricing-panel').hide();
      $('.redq-days-range-panel').hide();
    }
  });

  /**
   * Control hourly pricing types
   *
   * @since 4.0.7
   * @return null
   */
  var hourlyPricingType = $('#hourly_pricing_type').val();

  if (hourlyPricingType == 'hourly_general') {
    $('.redq-hourly-general-panel').show();
    $('.redq-hourly-range-panel').hide();
  } else {
    $('.redq-hourly-general-panel').hide();
    $('.redq-hourly-range-panel').show();
  }

  $('#hourly_pricing_type').change(function() {
    var hourlyPricingType = this.value;
    if (hourlyPricingType == 'hourly_general') {
      $('.redq-hourly-general-panel').show();
      $('.redq-hourly-range-panel').hide();
    } else {
      $('.redq-hourly-general-panel').hide();
      $('.redq-hourly-range-panel').show();
    }
  });

  /**
   * Control resource tabs
   *
   * @since 2.0.0
   * @return null
   */
  $('body').on(
    'change',
    'select#inventory_price_applicable_term_meta',
    function() {
      if ($(this).val() != 'one_time') {
        $('input#inventory_hourly_cost_termmeta')
          .parent('.form-field')
          .show();
      } else {
        $('input#inventory_hourly_cost_termmeta')
          .parent('.form-field')
          .hide();
      }
    }
  );

  /**
   * Control person tabs
   *
   * @since 2.0.0
   * @return null
   */

  $('body').on('change', 'select#inventory_person_payable_or_not', function() {
    if ($(this).val() != 'yes') {
      $('input#inventory_person_cost_termmeta')
        .parent('.form-field')
        .hide();
      $('select#inventory_person_price_applicable_term_meta')
        .parent('.form-field')
        .hide();
      $('input#inventory_peroson_hourly_cost_termmeta')
        .parent('.form-field')
        .hide();
    } else {
      $('input#inventory_person_cost_termmeta')
        .parent('.form-field')
        .show();
      $('select#inventory_person_price_applicable_term_meta')
        .parent('.form-field')
        .show();
      $('input#inventory_peroson_hourly_cost_termmeta')
        .parent('.form-field')
        .show();
    }
  });

  $('body').on(
    'change',
    'select#inventory_person_price_applicable_term_meta',
    function() {
      if ($(this).val() != 'one_time') {
        $('input#inventory_peroson_hourly_cost_termmeta')
          .parent('.form-field')
          .show();
      } else {
        $('input#inventory_peroson_hourly_cost_termmeta')
          .parent('.form-field')
          .hide();
      }
    }
  );

  /**
   * Control security deposit tabs
   *
   * @since 2.0.0
   * @return null
   */
  $('body').on(
    'change',
    'select#inventory_sd_price_applicable_term_meta',
    function() {
      if ($(this).val() != 'one_time') {
        $('input#inventory_sd_hourly_cost_termmeta')
          .parent('.form-field')
          .show();
      } else {
        $('input#inventory_sd_hourly_cost_termmeta')
          .parent('.form-field')
          .hide();
      }
    }
  );

  /**
   * Control settings tabs
   *
   * @since 2.0.0
   * @return null
   */
  $('#rnb_setting_tabs').tabs();

  $('.inventory-resources').select2({
    placeholder: 'Select resources',
    tags: true,
  });
  $('select').on('select2:select', function(evt) {
    var element = evt.params.data.element;
    var $element = $(element);

    $element.detach();
    $(this).append($element);
    $(this).trigger('change');
  });
});
