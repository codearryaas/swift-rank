/**
 * FAQ Block Frontend Script
 *
 * Handles accordion toggle functionality
 */

document.addEventListener( 'DOMContentLoaded', function() {
	// Find all FAQ blocks
	const faqBlocks = document.querySelectorAll( '.wp-block-swift-rank-faq' );

	faqBlocks.forEach( function( block ) {
		const enableToggle = block.dataset.enableToggle === 'true';
		const openFirst = block.dataset.openFirst === 'true';

		if ( ! enableToggle ) {
			// If toggle is disabled, show all answers
			const allItems = block.querySelectorAll( '.swift-rank-faq-item' );
			allItems.forEach( item => item.classList.add( 'is-open' ) );
			return;
		}

		const faqItems = block.querySelectorAll( '.swift-rank-faq-item' );

		// Open first item if enabled
		if ( openFirst && faqItems.length > 0 ) {
			faqItems[0].classList.add( 'is-open' );
		}

		// Add click handlers to all FAQ items
		faqItems.forEach( function( item ) {
			const questionWrapper = item.querySelector( '.faq-item-question-wrapper' );

			if ( questionWrapper ) {
				questionWrapper.addEventListener( 'click', function() {
					// Toggle the clicked item
					item.classList.toggle( 'is-open' );
				} );

				// Make it keyboard accessible
				questionWrapper.setAttribute( 'role', 'button' );
				questionWrapper.setAttribute( 'tabindex', '0' );
				questionWrapper.setAttribute( 'aria-expanded', item.classList.contains( 'is-open' ) );

				// Handle keyboard events
				questionWrapper.addEventListener( 'keydown', function( e ) {
					if ( e.key === 'Enter' || e.key === ' ' ) {
						e.preventDefault();
						item.classList.toggle( 'is-open' );
						questionWrapper.setAttribute( 'aria-expanded', item.classList.contains( 'is-open' ) );
					}
				} );
			}
		} );
	} );
} );
