<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport"
	content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="renderer" content="webkit">
<title></title>
<link rel="stylesheet" href="/Public/css/pintuer.css">
<link rel="stylesheet" href="/Public/css/admin.css">
<script src="/Public/js/jquery.js"></script>
<script src="/Public/js/pintuer.js"></script>
</head>
<body>
	<div class="panel admin-panel">
		<div class="panel-head" id="add">
			<strong><span class="icon-pencil-square-o"></span>增加内容</strong>
		</div>
		<div class="body-content">
			<form method="post" class="form-x" action="">
				<div class="form-group">
					<div class="label">
						<label>所属系列：</label>
					</div>
					<div class="field">
						<select name="Seriesname" class="input w50">
							<option value="">请选择分类</option>
							<option value="">产品分类1</option>
							<option value="">产品分类2</option>
							<option value="">产品分类3</option>
							<option value="">产品分类4</option>
						</select>
						<div class="tips"></div>
					</div>
				</div>
				<div class="form-group">
					<div class="label">
						<label>动画名称：</label>
					</div>
					<div class="field">
						<input type="text" class="input" name="cartoonname" value="" />
					</div>
				</div>
				<div class="form-group">
					<div class="label">
						<label>图片素材：</label>
					</div>
					<div class="field">
						<input type="text" id="url1" name="img" class="input tips"
							style="width: 25%; float: left;" value="" data-toggle="hover"
							data-place="right" data-image="" /> <input type="button"
							class="button bg-blue margin-left" id="image1" value="+ 浏览上传"
							style="float: left;">
						<div class="tipss">图片尺寸：500*500</div>
					</div>
				</div>
				<div class="form-group">
					<div class="label">
						<label>演示类型：</label>
					</div>
					<div class="field">
						<input name="showtype" type="radio" value="0" />
						<input name="showtype" type="radio" value="1" />
						<div class="tips"></div>
					</div>
				</div>
				<div class="form-group">
					<div class="label">
						<label>文件上传：</label>
					</div>
					<div class="field">
						<textarea name="content" class="input"
							style="height: 450px; border: 1px solid #ddd;"></textarea>
						<div class="tips"></div>
					</div>
				</div>
				<div class="form-group">
					<div class="label">
						<label></label>
					</div>
					<div class="field">
						<button class="button bg-main icon-check-square-o" type="submit">
							提交</button>
					</div>
				</div>
			</form>
		</div>
	</div>

</body>
</html>