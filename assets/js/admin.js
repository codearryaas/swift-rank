/**
 * Swift Rank Admin JavaScript
 */

(function ($) {
	'use strict';

	$(document).ready(function () {

		// Accordion functionality
		$(document).on('click', '.swift-rank-accordion-header', function () {
			var $item = $(this).closest('.swift-rank-accordion-item');
			var wasActive = $item.hasClass('active');

			// Close all accordion items
			$('.swift-rank-accordion-item').removeClass('active');

			// Toggle clicked item
			if (!wasActive) {
				$item.addClass('active');
			}
		});

		// Open first accordion item by default
		$('.swift-rank-accordion-item:first').addClass('active');

		// Schema type selector change handler
		$('#swift_rank_template_schema_type').on('change', function () {
			var selectedType = $(this).val();
			var $preview = $('#swift-rank-template-preview');

			if (!selectedType) {
				$preview.hide();
				return;
			}

			// Show loading state
			$preview.html('<p class="description" style="padding: 12px;">Loading template preview...</p>').show();

			// Load template via AJAX
			$.ajax({
				url: ajaxurl, // WordPress global
				type: 'POST',
				data: {
					action: 'swift_rank_get_template_fields',
					schema_type: selectedType
				},
				success: function (response) {
					if (response.success && response.data && response.data.preview) {
						$preview.html('<h4>Template Preview</h4><p class="description">This structure will be used for matching posts.</p>' + response.data.preview);
					} else {
						$preview.html(
							'<p class="description" style="padding: 12px;">Template for <strong>' + selectedType + '</strong> loaded. ' +
							'Variables will be replaced when applied to posts.</p>'
						);
					}
				},
				error: function () {
					$preview.html(
						'<p class="description" style="padding: 12px;">Template for <strong>' + selectedType + '</strong> selected.</p>'
					);
				}
			});
		});

		// Initialize variable insertion buttons
		function initVariableButtons() {
			// Remove existing dropdowns first
			$('.swift-rank-variable-dropdown').remove();

			// Handle variable insertion button clicks
			$(document).off('click', '.swift-rank-insert-variable-btn').on('click', '.swift-rank-insert-variable-btn', function (e) {
				e.preventDefault();
				var $btn = $(this);
				var targetId = $btn.data('target');
				var $target = $('#' + targetId);

				// Remove any existing dropdown
				$('.swift-rank-variable-dropdown').remove();

				// Create dropdown menu
				var $dropdown = $('<div class="swift-rank-variable-dropdown"></div>');
				$dropdown.css({
					position: 'absolute',
					top: $btn.position().top + $btn.outerHeight() + 5,
					right: '5px',
					backgroundColor: '#fff',
					border: '1px solid #ccc',
					borderRadius: '4px',
					boxShadow: '0 2px 8px rgba(0,0,0,0.15)',
					zIndex: 10000,
					minWidth: '250px',
					maxHeight: '400px',
					overflowY: 'auto'
				});

				// Build dropdown content
				var html = '';
				$.each(schemaVariables, function (category, variables) {
					html += '<div class="swift-rank-var-category" style="padding: 8px 12px; background: #f0f0f1; font-weight: 600; font-size: 11px; text-transform: uppercase; color: #50575e; border-top: 1px solid #ddd;">' + category + '</div>';
					$.each(variables, function (index, variable) {
						html += '<div class="swift-rank-var-item" data-value="' + variable.value + '" style="padding: 8px 12px; cursor: pointer; font-size: 12px; border-bottom: 1px solid #f0f0f1;">' +
							'<strong>' + variable.label + '</strong><br>' +
							'<code style="font-size: 11px; color: #666;">' + variable.value + '</code>' +
							'</div>';
					});
				});

				$dropdown.html(html);

				// Add close button
				var $closeBtn = $('<div style="position: sticky; top: 0; background: #2271b1; color: white; padding: 8px 12px; cursor: pointer; text-align: center; font-size: 11px; font-weight: 600;">✕ Close</div>');
				$dropdown.prepend($closeBtn);

				// Append to field container
				$btn.parent().append($dropdown);

				// Handle variable selection
				$dropdown.find('.swift-rank-var-item').on('click', function () {
					var variable = $(this).data('value');
					insertVariableAtCursor($target[0], variable);
					$dropdown.remove();
				});

				// Handle close button
				$closeBtn.on('click', function () {
					$dropdown.remove();
				});

				// Close dropdown when clicking outside
				setTimeout(function () {
					$(document).on('click.tpVariableDropdown', function (e) {
						if (!$(e.target).closest('.swift-rank-variable-dropdown, .swift-rank-insert-variable-btn').length) {
							$dropdown.remove();
							$(document).off('click.tpVariableDropdown');
						}
					});
				}, 100);

				// Hover effect
				$dropdown.find('.swift-rank-var-item').on('mouseenter', function () {
					$(this).css('backgroundColor', '#f0f0f1');
				}).on('mouseleave', function () {
					$(this).css('backgroundColor', '#fff');
				});
			});
		}

		// Insert variable at cursor position
		function insertVariableAtCursor(field, variable) {
			var isTextarea = field.tagName.toLowerCase() === 'textarea';
			var isInput = field.tagName.toLowerCase() === 'input';

			if (!isTextarea && !isInput) {
				return;
			}

			// Get current selection
			var startPos = field.selectionStart;
			var endPos = field.selectionEnd;
			var currentValue = $(field).val();

			// Insert variable at cursor position
			var newValue = currentValue.substring(0, startPos) + variable + currentValue.substring(endPos);
			$(field).val(newValue);

			// Set cursor position after the inserted variable
			var newCursorPos = startPos + variable.length;
			field.setSelectionRange(newCursorPos, newCursorPos);

			// Focus the field
			field.focus();

			// Trigger change event
			$(field).trigger('change');
		}

		// Initialize on page load
		initVariableButtons();

		// Auto-save warning
		var originalValues = {};
		$('.swift-rank-metabox input, .swift-rank-metabox textarea, .swift-rank-metabox select').each(function () {
			var $field = $(this);
			originalValues[$field.attr('name')] = $field.val();
		});

		$(window).on('beforeunload', function () {
			var hasChanges = false;
			$('.swift-rank-metabox input, .swift-rank-metabox textarea, .swift-rank-metabox select').each(function () {
				var $field = $(this);
				if (originalValues[$field.attr('name')] !== $field.val()) {
					hasChanges = true;
					return false;
				}
			});

			if (hasChanges) {
				return 'You have unsaved changes. Are you sure you want to leave?';
			}
		});

		// Remove warning when form is submitted
		$('form').on('submit', function () {
			$(window).off('beforeunload');
		});

		// Knowledge Graph Type Toggle
		function toggleKnowledgeGraphFields() {
			var type = $('#swift_rank_settings_organization_type').val();
			var $personFields = $('.swift-rank-person-only').closest('tr');
			var $orgFields = $('.swift-rank-organization-only').closest('tr');
			var $businessFields = $('.swift-rank-localbusiness-only').closest('tr');
			var $nameLabel = $('label[for="swift_rank_settings_organization_name"]');

			// Fields to hide for Person
			var $logoRow = $('#swift_rank_settings_organization_logo').closest('tr');

			if ('Person' === type) {
				$personFields.show();
				$orgFields.hide();
				$businessFields.hide();
				$logoRow.hide();
				$nameLabel.text('Person Name');
			} else if ('LocalBusiness' === type) {
				$personFields.hide();
				$orgFields.show();
				$businessFields.show();
				$logoRow.show();
				$nameLabel.text('Business Name');
			} else {
				// Organization
				$personFields.hide();
				$orgFields.show();
				$businessFields.hide();
				$logoRow.show();
				$nameLabel.text('Organization Name');
			}
		}

		// Init toggle
		if ($('#swift_rank_settings_organization_type').length) {
			toggleKnowledgeGraphFields();
			$('#swift_rank_settings_organization_type').on('change', toggleKnowledgeGraphFields);
		}

		// Repeater field functionality
		$(document).on('click', '.swift-rank-add-repeater-item', function (e) {
			e.preventDefault();
			var $btn = $(this);
			var repeaterId = $btn.data('repeater-id');
			var $container = $('#' + repeaterId);
			var $items = $container.find('.swift-rank-repeater-item');
			var newIndex = $items.length;

			// Clone the first item or create a new one
			var $template;
			if ($items.length > 0) {
				$template = $items.first().clone();

				// Clear values and update indices
				$template.find('input[type="text"], input[type="url"], input[type="time"], textarea').val('');
				$template.find('select').prop('selectedIndex', 0);
				$template.find('input, textarea, select').each(function () {
					var $field = $(this);
					var name = $field.attr('name');
					if (name) {
						// Update the index in the name attribute
						name = name.replace(/\[\d+\]/, '[' + newIndex + ']');
						$field.attr('name', name);
					}

					var id = $field.attr('id');
					if (id) {
						id = id.replace(/_\d+(_|$)/, '_' + newIndex + '$1');
						$field.attr('id', id);
					}
				});

				// Update data-target attributes on Insert Variable buttons
				$template.find('.swift-rank-insert-variable-btn').each(function () {
					var $varBtn = $(this);
					var target = $varBtn.data('target');
					if (target) {
						target = target.replace(/_\d+(_|$)/, '_' + newIndex + '$1');
						$varBtn.attr('data-target', target);
					}
				});
			} else {
				// Create a basic template if no items exist
				var repeaterPath = $btn.closest('.swift-rank-repeater').data('repeater-path');
				$template = $('<div class="swift-rank-repeater-item" style="background: #f9f9f9; padding: 12px; margin-bottom: 8px; border-radius: 4px; position: relative;">' +
					'<button type="button" class="button button-small button-link-delete swift-rank-remove-repeater-item" style="position: absolute; top: 8px; right: 8px; color: #b32d2e;">' +
					'<span class="dashicons dashicons-no-alt" style="vertical-align: middle;"></span> Remove</button>' +
					'<div style="position: relative; padding-right: 80px;">' +
					'<input type="text" name="swift_rank_template_fields[' + repeaterPath + '][' + newIndex + ']" class="widefat swift-rank-field-input" style="font-family: monospace; font-size: 12px;" placeholder="Enter value" />' +
					'</div>' +
					'</div>');
			}

			$container.append($template);

			// Scroll to the new item
			$template[0].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
		});

		// Remove repeater item
		$(document).on('click', '.swift-rank-remove-repeater-item', function (e) {
			e.preventDefault();
			e.stopPropagation();
			var $item = $(this).closest('.swift-rank-repeater-item');
			var $container = $item.closest('.swift-rank-repeater-items');

			// Don't allow removing if it's the only item - just clear it instead
			if ($container.find('.swift-rank-repeater-item').length === 1) {
				$item.find('input, textarea, select').val('');
				return;
			}

			// Confirm removal
			if (confirm('Are you sure you want to remove this item?')) {
				$item.fadeOut(300, function () {
					$(this).remove();
				});
			}
		});

		// Image upload functionality
		var tpMediaUploader;
		$(document).on('click', '.swift-rank-upload-image-btn', function (e) {
			e.preventDefault();

			var $btn = $(this);
			var targetId = $btn.data('target');
			var $targetField = $('#' + targetId);

			// If the media frame already exists, reopen it
			if (tpMediaUploader) {
				tpMediaUploader.open();
				return;
			}

			// Create the media frame
			tpMediaUploader = wp.media({
				title: 'Select or Upload Image',
				button: {
					text: 'Use this image'
				},
				multiple: false
			});

			// When an image is selected, run a callback
			tpMediaUploader.on('select', function () {
				var attachment = tpMediaUploader.state().get('selection').first().toJSON();

				// Set the image URL to the field
				$targetField.val(attachment.url);
				$targetField.trigger('change');

				// Show a preview - check both schema field and settings field containers
				var $container = $targetField.closest('.swift-rank-field');
				if ($container.length === 0) {
					$container = $targetField.parent();
				}

				var $previewContainer = $container.find('.swift-rank-image-preview');
				if ($previewContainer.length === 0) {
					$previewContainer = $('<div class="swift-rank-image-preview" style="margin-top: 8px;"></div>');
					$container.append($previewContainer);
				}

				$previewContainer.html(
					'<img src="' + attachment.url + '" style="max-width: 200px; max-height: 150px; border-radius: 4px; border: 1px solid #ddd; padding: 4px; background: #f9f9f9;" />' +
					'<div style="font-size: 11px; color: #666; margin-top: 4px;">' +
					'<strong>Size:</strong> ' + attachment.width + ' × ' + attachment.height + 'px' +
					'</div>'
				);
			});

			// Open the media frame
			tpMediaUploader.open();
		});

		// Opening hours closed checkbox toggle
		$(document).on('change', '.swift-rank-closed-checkbox', function () {
			var $checkbox = $(this);
			var $row = $checkbox.closest('.swift-rank-hours-row');

			if ($checkbox.is(':checked')) {
				$row.addClass('closed');
			} else {
				$row.removeClass('closed');
			}
		});

	});

})(jQuery);
