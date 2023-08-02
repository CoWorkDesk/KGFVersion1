<script type="text/template" id="fusion-builder-block-module-google-map-preview-template">
	<h4 class="fusion_module_title"><span class="fusion-module-icon {{ fusionAllElements[element_type].icon }}"></span>{{ fusionAllElements[element_type].name }}</h4>

	<# if ( 'embed' === params.api_type ) { #>
		{{ params.embed_address }}
	<# } else { #>
		{{ params.address }}
	<# } #>

</script>
