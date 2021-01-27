/*! js-cookie v3.0.0-rc.1 | MIT */
!function(e,t){"object"==typeof exports&&"undefined"!=typeof module?module.exports=t():"function"==typeof define&&define.amd?define(t):(e=e||self,function(){var n=e.Cookies,r=e.Cookies=t();r.noConflict=function(){return e.Cookies=n,r}}())}(this,function(){"use strict";function e(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)e[r]=n[r]}return e}var t={read:function(e){return e.replace(/(%[\dA-F]{2})+/gi,decodeURIComponent)},write:function(e){return encodeURIComponent(e).replace(/%(2[346BF]|3[AC-F]|40|5[BDE]|60|7[BCD])/g,decodeURIComponent)}};return function n(r,o){function i(t,n,i){if("undefined"!=typeof document){"number"==typeof(i=e({},o,i)).expires&&(i.expires=new Date(Date.now()+864e5*i.expires)),i.expires&&(i.expires=i.expires.toUTCString()),t=encodeURIComponent(t).replace(/%(2[346B]|5E|60|7C)/g,decodeURIComponent).replace(/[()]/g,escape),n=r.write(n,t);var c="";for(var u in i)i[u]&&(c+="; "+u,!0!==i[u]&&(c+="="+i[u].split(";")[0]));return document.cookie=t+"="+n+c}}return Object.create({set:i,get:function(e){if("undefined"!=typeof document&&(!arguments.length||e)){for(var n=document.cookie?document.cookie.split("; "):[],o={},i=0;i<n.length;i++){var c=n[i].split("="),u=c.slice(1).join("=");'"'===u[0]&&(u=u.slice(1,-1));try{var f=t.read(c[0]);if(o[f]=r.read(u,f),e===f)break}catch(e){}}return e?o[e]:o}},remove:function(t,n){i(t,"",e({},n,{expires:-1}))},withAttributes:function(t){return n(this.converter,e({},this.attributes,t))},withConverter:function(t){return n(e({},this.converter,t),this.attributes)}},{attributes:{value:Object.freeze(o)},converter:{value:Object.freeze(r)}})}(t,{path:"/"})});

var pacbar =
{
	erf: false,
	da: false,
	dc: false,
	dbi: false,
	oc: false,

	//Bind
	init: function()
	{
		pacbar.pacbar_read();
		pacbar.pacbar_update();

		jQuery(".pacbar ul li").on("click", "a", function()
		{
			if (jQuery(this).hasClass("pacbar-erf"))
				pacbar.erf = !(pacbar.erf);
			else if (jQuery(this).hasClass("pacbar-da"))
				pacbar.da = !(pacbar.da);
			else if (jQuery(this).hasClass("pacbar-dc"))
				pacbar.dc = !(pacbar.dc);
			else if (jQuery(this).hasClass("pacbar-dbi"))
				pacbar.dbi = !(pacbar.dbi);
			else if (jQuery(this).hasClass("pacbar-ra"))
				pacbar.pacbar_ra();

			pacbar.pacbar_write();
			pacbar.pacbar_update();
		});

		jQuery(".pacbar .pacbar-control").on("click", "a", function()
		{
			//Refactor
			pacbar.pacbar_toggle();
			pacbar.pacbar_write();
			pacbar.pacbar_update();
		});



		return false;
	},

	pacbar_update: function()
	{
		jQuery(".pacbar ul li a.pacbar-erf").removeClass("enabled");
		if (pacbar.erf === true)
		{
			jQuery(".pacbar ul li a.pacbar-erf").addClass("enabled");
			jQuery("body").addClass("pacbar-erf");
		}
		else
		{
			jQuery("body").removeClass("pacbar-erf");
		}


		jQuery(".pacbar ul li a.pacbar-da").removeClass("enabled");
		if (pacbar.da === true)
		{
			jQuery(".pacbar ul li a.pacbar-da").addClass("enabled");
			jQuery("body").addClass("pacbar-da");
			jQuery(".ls-container").each(function()
			{
				var slid = jQuery(this).attr("id");
				jQuery("#" + slid).layerSlider("pause");
			});
		}
		else
		{
			jQuery("body").removeClass("pacbar-da");
			jQuery(".ls-container").each(function()
			{
				var slid = jQuery(this).attr("id");
				jQuery("#" + slid).layerSlider("resume");
			});

		}

		jQuery(".pacbar ul li a.pacbar-dc").removeClass("enabled");
		if (pacbar.dc === true)
		{
			jQuery(".pacbar ul li a.pacbar-dc").addClass("enabled");
			jQuery("body").addClass("pacbar-dc");
		}
		else
		{
			jQuery("body").removeClass("pacbar-dc");
		}


		jQuery(".pacbar ul li a.pacbar-dbi").removeClass("enabled");
		if (pacbar.dbi === true)
		{
			jQuery(".pacbar ul li a.pacbar-dbi").addClass("enabled");
			jQuery("body").addClass("pacbar-dbi");
		}
		else
		{
			jQuery("body").removeClass("pacbar-dbi");
		}


		if (pacbar.oc === true)
			jQuery(".pacbar").removeClass("closed");
		else
			jQuery(".pacbar").addClass("closed");

	},

	//Toggle Enable Readable Font
	pacbar_erf: function()
	{

	},

	//Toggle Disable Animations
	pacbar_da: function()
	{


	},

	//Toggle Columns
	pacbar_dc: function()
	{


	},

	//Toggle Background Images
	pacbar_dbi: function()
	{


	},

	//Reset All
	pacbar_ra: function()
	{
		pacbar.erf = false;
		pacbar.da = false;
		pacbar.dc = false;
		pacbar.dbi = false;

		pacbar.pacbar_write();
		pacbar.pacbar_update();

	},

	pacbar_write: function()
	{
		Cookies.set("pb_erf", pacbar.erf);
		Cookies.set("pb_da", pacbar.da);
		Cookies.set("pb_dc", pacbar.dc);
		Cookies.set("pb_dbi", pacbar.dbi);
		Cookies.set("pb_oc", pacbar.oc);
	},

	pacbar_read: function()
	{
		pacbar.erf = (Cookies.get("pb_erf") == "true") ? true : false;
		pacbar.da = (Cookies.get("pb_da") == "true") ? true : false;
		pacbar.dc = (Cookies.get("pb_dc") == "true") ? true : false;
		pacbar.dbi = (Cookies.get("pb_dbi") == "true") ? true : false;
		pacbar.oc = (Cookies.get("pb_oc") == "true") ? true : false;
	},

	//Handle open and close, cookies handled in init
	pacbar_toggle: function()
	{
		//Anim?
		pacbar.oc = !(pacbar.oc);
		pacbar.pacbar_write();
		pacbar.pacbar_update();
	}
};

jQuery(document).ready(function($)
{
	pacbar.init();
});