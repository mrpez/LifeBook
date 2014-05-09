<h2>Notes</h2>
<link href='/lib/css/notes.css' rel='stylesheet' type='text/css'>
<script src="/lib/js/jquery.1.11.0.min.js"></script>
<script type="text/javascript">
	window.onload = function() {
			lbn.refreshTree();
		}
		
	lbn = {};
	
	lbn.refreshTree = function() {
		$.ajax({
			url:'/ajax.php'
			, type:'post'
			, data:{
				method:'getBaseTree'
			}
			, dataType:'json'
			, success:function(data) {
				var treeContainer = document.getElementById('treeContainer');
				var noteContainer = document.getElementById('noteContainer');
				
				var tree = ['<input type="text" id="noteName"/><a href="javascript:void(0);" onclick="lbn.addNote();">Add Note</a>'];
				tree.push('<ul id="noteTree">')
				
				for( var i = 0; i < data['notes'].length; i++) {
					tree.push('<li onclick="lbn.openNote(');
					tree.push(data['notes'][i]['note_id']);
					tree.push(');">');
					tree.push(data['notes'][i]['title']);
					tree.push('</li>');
				}
				tree.push('</ul>');
				
				treeContainer.innerHTML = tree.join('');
				noteContainer.innerHTML = 'Select a note from the left.';
			}
			, error: function() {
				alert('An error occured loading notes. Please refresh this page and try again.');
			}
		});
	}
	
	lbn.openNote = function(noteId) {
		$.ajax({
			url:'/ajax.php'
			, type:'post'
			, data:{
				method:'getNote'
				, noteId:noteId
			}
			, dataType:'json'
			, success:function(data) {
				
			}
			, error:function() {
				alert('An error occured opening note.');
			}
		});
	}
	
	lbn.addNote = function() {
		$.ajax({
			url:'/ajax.php'
			, type:'post'
			, data:{
				method:'createNote'
				, noteName:document.getElementById('noteName').value
			}
			, dataType:'json'
			, success:function(data) {
				lbn.refreshTree();
			}
			, error:function() {
				alert('An unexpected error occured adding note.');
			}
		});
	}
</script>

<div id="treeContainer">
</div>
<div id="noteContainer">	
	<img src="/lib/img/throbber.gif"><br />
	Loading, Please Wait...
</div>
<div class="clearFix"></div>
