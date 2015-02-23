var create_shortcode_string = {
		 str:"",
		 main_string:"",
		 start_string:"[sub_page",
		 end_string:"]",
		 str_title:"",
		 str_sort_order:"",
		 str_sort_by:"",
		 str_exclude_page:"",
		 str_depth_level:"",
		 str_sort_order_parent:"",
		
		config:{
			
		},
		
		init:function(){

			jQuery( "#title" ).change(function() {	

				create_shortcode_string.createString1(jQuery(this));
		     });
			
			jQuery( "#title" ).trigger('change');

		},
		
		createString1:function(obj){
			
			if(obj.val() != '')
			{	
				create_shortcode_string.str_title="title='"+obj.val()+"'";
			}
			else
			{
				create_shortcode_string.str_title="";
			}
			create_shortcode_string.appendStr();
		},

		

		appendStr:function(){
			create_shortcode_string.main_string=create_shortcode_string.start_string +" "+ create_shortcode_string.str_title +" "+create_shortcode_string.end_string;
			
			jQuery("#shortcode").html(create_shortcode_string.main_string);		
			
		},
};	
jQuery(document).ready(function(){
	create_shortcode_string.init();
});

