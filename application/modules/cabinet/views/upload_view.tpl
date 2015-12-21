 <div class="block-header">
      <h3>Загрузка треков</h3>
</div>
<span><i>Максимальный размер файла - 20мб.</i></span><br /><br />
	<form id="upload" method="post" action="<?php echo(SITEURL); ?>/validation/addtrack" enctype="multipart/form-data">
			<div id="drop">
			Перетащите файлы сюда
<br />
				<a>Выбрать</a>
				<input type="file" name="upl" multiple accept="audio/mp3" />
			</div>

			<ul>
				<!-- The file uploads will be shown here -->
			</ul>

		</form>
