import domReady from '@wordpress/dom-ready';

domReady( function () {
	// Tab navigation
	const tabs = document.querySelectorAll( '.wpbe-nav-item' );
	const panels = document.querySelectorAll( '.wpbe-panel' );

	if ( tabs.length ) {
		const storageKey = 'wpbe_active_tab';
		const savedTab = localStorage.getItem( storageKey );

		const activate = ( tabId ) => {
			tabs.forEach( ( tab ) => {
				const isActive = tab.dataset.tab === tabId;
				tab.classList.toggle( 'is-active', isActive );
				tab.setAttribute( 'aria-selected', isActive ? 'true' : 'false' );
			} );

			panels.forEach( ( panel ) => {
				panel.classList.toggle( 'is-active', panel.dataset.panel === tabId );
			} );

			localStorage.setItem( storageKey, tabId );
		};

		tabs.forEach( ( tab ) => {
			tab.addEventListener( 'click', () => activate( tab.dataset.tab ) );
		} );

		// Restore saved tab
		if ( savedTab && document.querySelector( `[data-tab="${ savedTab }"]` ) ) {
			activate( savedTab );
		}
	}

	// Toggle all checkboxes in a section
	document.querySelectorAll( '.wpbe-toggle-all' ).forEach( ( button ) => {
		button.addEventListener( 'click', () => {
			const card = button.closest( '.wpbe-card' );
			if ( ! card ) {
				return;
			}

			const checkboxes = card.querySelectorAll( 'input[type="checkbox"]' );
			if ( ! checkboxes.length ) {
				return;
			}

			const anyUnchecked = Array.from( checkboxes ).some( ( cb ) => ! cb.checked );
			checkboxes.forEach( ( cb ) => {
				cb.checked = anyUnchecked;
			} );
		} );
	} );
} );
