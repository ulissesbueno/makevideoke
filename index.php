<link rel="stylesheet" type="text/css" href="lyric.css">
<script type="text/javascript" src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
	$(function(){
		$.ajax({
			datatype:'JSON',
			url:'letra.json',
			success : function( data ){
				//alert(data.info.name);
				Init(data);

			},
			error: function( error ){
				
			}
		})		
	})

	function convert_lyric( lyric ){

		var ar = [];
		for( var l in lyric ){

			if( lyric[l].strech.length ){
				ar[lyric[l].sec] = [];
				for( var s in lyric[l].strech ){					
					ar[lyric[l].sec][lyric[l].strech[s].sec] = lyric[l].strech[s];
				}
			}
		}

		return ar;
	}

	var strech_ant = '';

	function Init(data){

		var lyric = convert_lyric( data.lyric );

		$("#player").trigger('load').trigger('play');
		$("#player").bind("timeupdate",function(){

			var sec = $(this).prop("currentTime");
			var pos = Math.round( sec );
        	var linha = '';
        		linha = [];
        	var options = '';
        	strech = pos;
    		if( strech_ant != strech ){
    			strech_ant = strech;

    			if( $(".seg"+pos).length ){
    				$('.line').removeClass('active');
    				$(".seg"+pos).addClass('active',1000);
    			}

    			if( lyric[pos] ){
        		
        			// pegas linhas do trecho
	    			for( var l in lyric[pos] ){
	    				var text = convert_colchete(lyric[pos][l].text,lyric[pos][l].sec);
	    				linha.push("<div class='line'>"+ text +"</div>");
	    			}

	    			$('.line').addClass('gone').hide( 'drop', { left: 500  }, 500, function(){ $(this).remove() } );
	    			
	    			$('#lyric').append( "<div class='strech'>"+linha.join('')+"</div>" );

	    			$('.line:not(.gone)').show( 'drop', { to: { left: $('.line:not(.gone)').position().left } }, 500 );

        		}
    			
    		}
        	
        	//$('#time').html( sec.toFixed(2) );
    	});
	}

	function convert_colchete( str, seg ){
		
		var re = /{(.*?)(:([0-9]*))*}/gi; 
		var m;
		var spans = [];
		var cls = '';
		var first = true;
		 
		while ((m = re.exec(str)) !== null) {
		    if (m.index === re.lastIndex) {
		        re.lastIndex++;
		    }
		    
		    if( m[3] ){
		    	cls = m[3];	
		    }else{
		    	if( first ){
		    		cls = seg;
		    		first = false;	
		    	}
		    }

		    spans.push("<span class='seg"+cls+"'>"+ m[1] +"</span>");
		}

		if( spans.length ) str = spans.join('');

		return str;

	}
</script>
<audio controls id='player'>
  <source src="musica.m4a" type="audio/mpeg">
</audio>
<div id='lyric'></div>
<div id='time'></div>

