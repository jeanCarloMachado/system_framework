

/**
 * manda todos os filhos de algum elemento
 * por ajax para o endereco especificado
 * @return {[type]} [description]
 */
function sendChild(address,fatherElement)
{
	result = '';

	childData = {};
	childArr = fatherElement.find('input');
	childArr = $.merge(childArr,fatherElement.find('select'));
	childArr.each(function() {
		childData[$(this).attr('name')] = $(this).val();
	});
	
	$.ajax({
		type: 'POST',
		url: address,
		data: 'pacman=' + JSON.stringify (childData),
		dataType: 'json',
		async: false,
		success: function (data) {
			result = data;
		}
	});
    return result;
}

/**
 * função de voltar no historico
 * funciona com algum elemento com a classe back
 * @return {[type]} [description]
 */
function back_pacman()
{
    $('.back').click(function(){
        history.back(-1);
    })
}



