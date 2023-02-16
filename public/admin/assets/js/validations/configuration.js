
jQuery("form[id='config_update']").validate({

  ignore: "",
    rules: {

      'logo': 
      {
        required: true,
        nowhitespace: true,
      },
      'desc': 
      {
        required: true,
        nowhitespace: true,
      },
      'company_name': 
      {
        required: true,
        nowhitespace: true,
        maxlength: 35
      },
      'font_color': 
      {
        required: true,     
      },
      'primary_color': 
      {
        required: true,
      },
      'theme_color':
      {
        required: true,
      },
      'font_english': 
      {
        required: true,
      },
      'font_arabic': 
      {
        required: true,
      },
      

      'en_main_heading': 
      {
        required: true,
        digits: true,
        range: [30, 48]
      },
      'en_paragraph': 
      {
        required: true,
        digits: true,
        range: [14, 18]
      },
      'en_card': 
      {
        required: true,
        digits: true,
        range: [20, 30]
      },
      'en_sub_heading': 
      {
        required: true,
        digits: true,
        range: [16, 22]
      },
      'en_button': 
      {
        required: true,
        digits: true,
        range: [14, 18]
      },
      'en_label': 
      {
        required: true,
        digits: true,
        range: [14, 18]
      },
      'ar_main_heading': 
      {
        required: true,
        digits: true,
        range: [30, 48]
      },
      'ar_paragraph': 
      {
        required: true,
        digits: true,
        range: [14, 18]
      },
      'ar_card': 
      {
        required: true,
        digits: true,
        range: [20, 30]
      },
      'ar_sub_heading': 
      {
        required: true,
        digits: true,
        range: [16, 22]
      },
      'ar_button': 
      {
        required: true,
        digits: true,
        range: [14, 18]
      },
      'ar_label': 
      {
        required: true,
        digits: true,
        range: [14, 18]
      }                           
        
      
   
    },
      
});

