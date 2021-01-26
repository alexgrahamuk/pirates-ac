var pacbar =
{
	erf: false,
	da: false,
	dc: false,
	dbi: false,
	ra: false,

	//Bind
	init: function()
	{
		$(".pacbar ul li").on("click", "a", function()
		{
			if ($(this).hasClass("pacbar-erf"))
				pacbar.pacbar_erf();
			else if ($(this).hasClass("pacbar-da"))
				pacbar.pacbar_da();
			else if ($(this).hasClass("pacbar-dc"))
				pacbar.pacbar_dc();
			else if ($(this).hasClass("pacbar-dbi"))
				pacbar.pacbar_dbi();
			else if ($(this).hasClass("pacbar-ra"))
				pacbar.pacbar_ra();
			else if ($(this).hasClass("pacbar-toggle"))
				pacbar.pacbar_toggle();
		});

		return false;
	},

	//Toggle Enable Readable Font
	pacbar_erf: function()
	{
		if (pacbar.ra === false)
			pacbar.erf = !(pacbar.erf);

		$(".pacbar ul li a.pacbar-erf").removeClass("enabled");
		if (pacbar.erf === true)
			$(".pacbar ul li a.pacbar-erf").addClass("enabled");
	},

	//Toggle Disable Adnimations
	pacbar_da: function()
	{
		if (pacbar.ra === false)
			pacbar.da = !(pacbar.da);

		$(".pacbar ul li a.pacbar-da").removeClass("enabled");
		if (pacbar.erf === true)
			$(".pacbar ul li a.pacbar-da").addClass("enabled");

	},

	//Toggle Columns
	pacbar_dc: function()
	{
		if (pacbar.ra === false)
			pacbar.dc = !(pacbar.dc);

		$(".pacbar ul li a.pacbar-dc").removeClass("enabled");
		if (pacbar.erf === true)
			$(".pacbar ul li a.pacbar-dc").addClass("enabled");

	},

	//Toggle Background Images
	pacbar_dbi: function()
	{
		if (pacbar.ra === false)
			pacbar.dbi = !(pacbar.dbi);

		$(".pacbar ul li a.pacbar-dbi").removeClass("enabled");
		if (pacbar.erf === true)
			$(".pacbar ul li a.pacbar-dbi").addClass("enabled");

	},

	//Reset All
	pacbar_ra: function()
	{
		pacbar.erf = false;
		pacbar.da = false;
		pacbar.dc = false;
		pacbar.dbi = false;
		pacbar.ra = true;

		pacbar.erf();
		pacbar.da();
		pacbar.dc();
		pacbar.dbi();

		pacbar.ra = false;
	},

	//Handle open and close, cookies handled in init
	pacbar_toggle: function()
	{

	}
};


(function($)
{
	'use strict';
	pacbar.init();
})( jQuery );