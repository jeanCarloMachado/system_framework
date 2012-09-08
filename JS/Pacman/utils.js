/**
 * arquivo de funçoes default do sistema
 */

/**
 * manda todos os filhos de algum elemento
 * por ajax para o endereco especificado
 * @return {[type]} [description]
 */
function sendChild(address,fatherElement)
{
	result = '';

	childData = {};
	childArr = fatherElement.children();
	childArr.each(function() {
		if($(this).is('input')) {	
			childData[$(this).attr('name')] = $(this).val();
		}
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