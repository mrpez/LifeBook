<?php
	
	if( !class_exists('Utility') ) {
		include(dirname(__FILE__) . '/class/Utility.php');
		$Utility = new Utility;
	}
	
	$Utility->validateLogin();
	
	$PDODB = $Utility->getPDO();
	
	include('header.php');
?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script type="text/javascript">
	function getCategory() {
		$.ajax({
			url:'/life/ajaxResponder.php'
			, type:'post'
			, data:{
				method:'getCategory'
				, category_id:document.getElementById('categoryId').value
			}
			, dataType:'json'
			, success:function(data) {
				console.dir(data);
			}
			, error:function() {
				alert('An unexpected error occured while gathering data.');
			}
		});
	}
	
	ct = {};
	
	ct.expandCategory = function(category_id, e)  {
		$.ajax({
			url:'/life/ajaxResponder.php'
			, type:'post'
			, data:{
				method:'getCategory'
				, category_id:category_id
			}
			, dataType:'json'
			, success:function(data) {
				try {
					var ot = e.originalTarget.parentNode;
				} catch(err) {
					var ot = e.srcElement.parentNode;
				}
				
				var addIn = '<ul>';
				
				for( var i = 0; i < data.children.length; i++ ) {
					addIn += '<li><span onclick="ct.expandCategory(\'' + data.children[i]['category_id'] + '\', event);">' + data.children[i]['leaf_name'] + '</span></li>';
				}
				
				addIn += '<li>' + ct.getAddNoteSpan(category_id) + '</li>';
				
				addIn += '</ul>';
				
				ot.innerHTML += addIn;
				
				document.getElementById('noteContents').innerHTML = data.note.note;
				
			}
			, error:function() {
				alert('An unexpected error occured while gathering data.');
			}
		});
	}

	ct.addNote = function(category_id, e) {
		try {
			var ot = e.originalTarget.parentNode;
		} catch(err) {
			var ot = e.srcElement.parentNode;
		}
		
		ot.innerHTML = '<input type="text" id="newNote" category_id="' + category_id + '" onkeyup="ct.postNote(event);"/>';
		document.getElementById('newNote').focus();
	}
	
	ct.postNote = function(e) {
		if( e.keyCode == 13
			&& document.getElementById('newNote').value.length > 0 ) {
			$.ajax({
				url:'/ajaxResponder.php'
				, type:'post'
				, data:{
					method:'addNote'
					, category_id
					
				}
				, dataType:'json'
				, success:function(data) {
				
				}
				, error:function() {
					alert('An unexpected error has occured while adding note!');
				}
			});
		}
		
		if( e.keyCode == 27 ) {
			ct.clearAddNotes();
		}
	}
	
	ct.clearAddNotes = function() {
		if( document.getElementById('newNote') ) {
			var note = document.getElementById('newNote');
			var category_id = ct.getAtribute(note, 'category_id');
			note.parentElement.innerHTML = ct.getAddNoteSpan(category_id);
		}
	}
	
	ct.getAddNoteSpan = function(category_id) {
		return '<span onclick="ct.addNote(' + category_id + ', event);">Add Note...</span>';
	}
	
	ct.getAtribute = function(element, attribute) {
		for(var i = 0; i < element.attributes.length; i++) {
			if( element.attributes[i].name.toString() == attribute.toString() ) {
				return element.attributes[i].nodeValue;
			}
		}
		throw 'Attribute not found: ' + attribute + '!';
	}
	
</script>

<style type="text/css">
	body {
		padding: 0;
		margin: 0;
	}
	div#categoryTreeContainer {
		border-right: 1px solid gray;
		min-height: 100%;
		width: 25%;
		float:left;
	}
	div#noteContainer {
		float:left;
	}
	div#hiddenPopup{
		position:absolute;
		left: 100%;
		top: 100%;
		margin-left: -50%;
		margin-top: -50%;
		background-color: white;
		border: 1px solid #666;
	}
</style>

<?php
	
	echo '<div style="min-height:100%;">
			<div style="width: 25%;" id="categoryTreeContainer">
				<ul>
					<li><span onclick="ct.expandCategory(1, event);">Life</span></li>
				</ul>
			</div>
			<div id="noteContainer">
				<div id="noteContents">Open a note from the category tree on the left.</div>
			</div>
			<div style="clear:both;"></div>
		  </div>
		  <div id="hiddenPopup" style="display:none;">
			
		  </div>';
	
	include('footer.php');
?>