	<script type="text/javascript">
	
		$(document).ready(function () {

			$(".pick-a-color").pickAColor({
                                showSpectrum            : false,
				showSavedColors         : true,
				saveColorsPerElement    : true,
				fadeMenuToggle          : true,
				showAdvanced		: false,
				showBasicColors         : true,
				showHexInput            : true,
				allowBlank		: true,
				inlineDropdown		: true
			});
			
		});
	
		</script>

			<input type="text" class="pick-a-color form-control" disabled>
