<script lang="ts">
	import '../app.css';
	import { onMount } from 'svelte';
	import { config } from '@fortawesome/fontawesome-svg-core';
	import '@fortawesome/fontawesome-svg-core/styles.css'; // <- penting untuk menghindari FOUC
	config.autoAddCss = false;

	function updateFavicon() {
		const favicon = document.getElementById('favicon');
		if (favicon instanceof HTMLLinkElement) {
			const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
			if (darkModeMediaQuery.matches) {
				favicon.href = './address-card-light.svg';
			} else {
				favicon.href = './address-card-dark.svg';
			}
		}
	}

	onMount(() => {
		updateFavicon();
		window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', updateFavicon);
	});

	let { children } = $props();
</script>

{@render children()}
