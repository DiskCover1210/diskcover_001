/*   @ Barras de progreso HTML5 animadas con Javascript
     @ author Agust�n Baraza (contacto@nosolocss.com)
     @ Copyright 2014 nosolocss.com. All rights reserved
     @ http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
     @ link http://www.nosolocss.com 	 */
	 //animateprogress("#car",<?php echo $tabla[$i]['canreg']; ?>,<?php echo $i; ?>,'<?php echo $tabla[$i]['tabla']; ?>');
	 function animateprogress1 (id, val, i)
	 {
		document.querySelector(id).innerHTML = "<progress id='car"+i+"' max='"+val+"' value='0'></progress>	<span></span>";    
	 }

function animateprogress (id, val, i, tabla){		

    //alert(" vs "+val);
	var getRequestAnimationFrame = function () {  /* <------- Declaro getRequestAnimationFrame intentando obtener la m�xima compatibilidad con todos los navegadores */
		return window.requestAnimationFrame ||
		window.webkitRequestAnimationFrame ||   
		window.mozRequestAnimationFrame ||
		window.oRequestAnimationFrame ||
		window.msRequestAnimationFrame ||
		function ( callback ){
			window.setTimeout(enroute, 1 / 60 * 1000);
		};
		
	};
	
	var fpAnimationFrame = getRequestAnimationFrame();   
	//var i = 0;
	var animacion = function () {
			
	if (i<=val) 
		{
			document.querySelector(id).setAttribute("value",i);      /* <----  Incremento el valor de la barra de progreso */
			document.querySelector(id+"+ span").innerHTML = i+" Tablas "+tabla;     /* <---- Incremento el porcentaje y lo muestro en la etiqueta span */
			i++;
			fpAnimationFrame(animacion);          /* <------------------ Mientras que el contador no llega al porcentaje fijado la funci�n vuelve a llamarse con fpAnimationFrame     */
		}
										
	}

		fpAnimationFrame(animacion);   /*  <---- Llamo la funci�n animaci�n por primera vez usando fpAnimationFrame para que se ejecute a 60fps  */
				
}