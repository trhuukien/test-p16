/**
 * Anowave Magento 2 Google Tag Manager Enhanced Ecommerce (UA) Tracking
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Anowave license that is
 * available through the world-wide-web at this URL:
 * https://www.anowave.com/license-agreement/
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category 	Anowave
 * @package 	Anowave_Ec
 * @copyright 	Copyright (c) 2024 Anowave (https://www.anowave.com/)
 * @license  	https://www.anowave.com/license-agreement/
 */

define(['jquery','mage/template','https://www.gstatic.com/charts/loader.js', 'Magento_Ui/js/modal/modal'], function($,template, charts, modal) 
{
	'use strict';
		
	return function(config, element) 
	{
		$('a[data-impression-model-about]').on('click', function()
		{
			/**
			 * Remove any previous stats
			 */
			$('[id=statistics]').remove();
			
			var content = $('<div/>').attr('id','statistics').html('Please wait...')

			$('<div />').append(content).modal(
			{
	            title: 'Local Google Tag Manager Self-Assessment statistics',
	            autoOpen: true,
	            closed: function () {},
	            buttons: 
		        [
			        {
		                text: 'I understand',
		                attr: 
			            {
		                    'data-action': 'confirm'
		                },
		                'class': 'action-primary'
		            }
		         ]
	         });

			return false;
		}).end().on('modalopened', function() 
		{ 
			$.getJSON(config.url, function(response)
			{
			      google.charts.load('current', {'packages':['corechart']});
				  google.charts.setOnLoadCallback(() => 
				  {
			            (new google.visualization.BarChart(document.getElementById('statistics'))).draw(google.visualization.arrayToDataTable
			    		(
							[
								[
									'Count', 'Count', { role: 'style' } 
								],
								[
									'Failed frontend ', response.placed, 'color:rgb(255,0,0); opacity: 0.4'
								],
								[
									'Failed backend  ', response.placed_admin, 'color:rgb(255,0,0); opacity: 0.4'
								],
								[
									'Tracked frontend ', response.tracked, 'color:rgb(0,184,91); opacity: 0.4'
								],
								[
									'Tracked backend ', response.tracked_admin, 'color:rgb(0,184,91); opacity: 0.4'
								],
								[
									'Total orders', response.total, 'color:rgb(0,138,255); opacity: 0.4'
								],
								[
									'Consent grant', response.consent_grant, 'color:rgb(0,138,255); opacity: 0.4'
								],
								[
									'Consent decline', response.consent_decline, 'color:rgb(255,0,0); opacity: 0.4'
								],
								[
									'Consent grant marketing', response.consent_grant_marketing, 'color:rgb(255,0,0); opacity: 0.4'
								],
								[
									'Consent grant preferences', response.consent_grant_preferences, 'color:rgb(255,0,0); opacity: 0.4'
								],
								[
									'Consent grant analytics', response.consent_grant_analytics, 'color:rgb(255,0,0); opacity: 0.4'
								],
								[
									'Consent grant userdata', response.consent_grant_userdata, 'color:rgb(255,0,0); opacity: 0.4'
								],
								[
									'Consent grant personalization', response.consent_grant_personalization, 'color:rgb(255,0,0); opacity: 0.4'
								]
							]
			    		), 
						{
							title:	'Placed/Tracked orders by type',
							width:	'auto',
							height: 400,
							legend: 'none'
						});  
				  });
			});
		}); 
	};
});