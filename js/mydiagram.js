$(function(){
$( pieChart );

function pieChart() {

  // Конфигурация
  var chartSizePercent = 60;                        // Радиус диаграммы, выраженный в процентах от размеров области рисования
  var sliceBorderWidth = 1;                         // Ширина (в пискселах) границы вокруг каждого сектора
  var sliceBorderStyle = "#fff";                    // Цвет границы вокруг каждого сектора
  var sliceGradientColour = "#ddd";                 // Цвет, который используется с одного конца диаграммы для создания градиента
  var maxPullOutDistance = 10;                      // Насколько далеко будет выдвигаться сектор из диаграммы
  var pullOutFrameStep = 4;                         // На сколько пикселей Hеперемещается сектор в каждом кадре анимации
  var pullOutFrameInterval = 40;                    // Сколько ms проходит между кадрами
  var pullOutLabelPadding = 50;                     // Отступ между выдвинутым сектором и его меткой
  var pullOutLabelFont = "italic 12px 'Trebuchet MS', Verdana, sans-serif";  // Шрифт метки выдвинутого сектора
  var pullOutValueFont = "bold 12px 'Trebuchet MS', Verdana, sans-serif";  // Шрифт значения выдвинутого сектора
  var pullOutValuePrefix = "";                     // Префикс значения выдвинутого сектора
  var pullOutShadowColour = "rgba( 0, 0, 0, .5 )";  // Цвет тени выдвинутого сектора
  var pullOutShadowOffsetX = 5;                     // Смещение по оси X (в пикселах) тени выдвинутого сектора
  var pullOutShadowOffsetY = 5;                     // Смещение по оси Y (в пикселах) тени выдвинутого сектора
  var pullOutShadowBlur = 5;                        // Насколько сильно размыта тень выдвинутого сектора
  var pullOutBorderWidth = 1;                       // Ширина (в пикселах) границы выдвинутого сектора
  var pullOutBorderStyle = "#333";                  // Цвет границы выдвинутого сектора
  var chartStartAngle = -.5 * Math.PI;              // Начало диаграммы на 12 часов, а не на 3-х

  // Объявдение некоторых перменных для диаграммы
  var canvas;                       // Область рисования на странице
  var currentPullOutSlice = -1;     // Сектор, который выдвинут в текущий момент(-1 = нет выдвинутого сектора)
  var currentPullOutDistance = 0;   // На сколько пикселей смещен текущий выдвигаемый сектор в ходе анимации
  var animationId = 0;              // ID интервала анимации, созданный с помощью setInterval()
  var chartData = [];               // Данные диаграммы (метки, занчения, углыы)
  var chartColours = [];            // Цвета диаграммы (получены из таблицы HTML)
  var totalValue = 0;               // Сумма всех значений в диаграмме
  var canvasWidth;                  // Ширина области рисования
  var canvasHeight;                 // Высота области рисования
  var centreX;                      // Координата X центра диаграммы на области рисования
  var centreY;                      // Координата Y центра диаграммы на области рисования
  var chartRadius;                  // Радиус диаграммы в пикселах

  // Инициализируем данные и рисуем диаграмму
  init();


  /**
   * Устанавливаем для диаграммы данные и цвета, а также устанавливаем обработчики события click
   * для диаграммы и таблицы. Рисуем диаграмму.
   */

  function init() {

    // Получаем область рисования на странице
    canvas = document.getElementById('chart');

    // Выходим, если браузер не имеет возможности рисовать
    if ( typeof canvas.getContext === 'undefined' ) return;

    // Инициализуем некоторые свойства области рисования и диаграммы
    canvasWidth = canvas.width;
    canvasHeight = canvas.height;
    centreX = canvasWidth / 2;
    centreY = canvasHeight / 2;
    chartRadius = Math.min( canvasWidth, canvasHeight ) / 2 * ( chartSizePercent / 100 );

    // Получаем данные из таблицы
    // и устанавливаем обработчики события click для ячеек таблицы
    
    var currentRow = -1;
    var currentCell = 0;

    $('#chartData td').each( function() {
      currentCell++;
      if ( currentCell % 2 != 0 ) {
        currentRow++;
        chartData[currentRow] = [];
        chartData[currentRow]['label'] = $(this).text();
      } else {
       var value = parseFloat($(this).text());
       totalValue += value;
       value = value.toFixed(2);
       chartData[currentRow]['value'] = value;
      }

      // Сохраняем индекс сектора в ячейке и привязываем к ней обработчик события click
      $(this).data( 'slice', currentRow );
      $(this).click( handleTableClick );

      // Получаем и сохраняем цвет ячейки
      if ( rgb = $(this).css('color').match( /rgb\((\d+), (\d+), (\d+)/) ) {
        chartColours[currentRow] = [ rgb[1], rgb[2], rgb[3] ];
      } else if ( hex = $(this).css('color').match(/#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/) ) {
        chartColours[currentRow] = [ parseInt(hex[1],16) ,parseInt(hex[2],16), parseInt(hex[3], 16) ];
      } else {
        alert( "Ошибка: Цвет не может быть определен! Пожалуйста, задайте таблицу цветов в формате '#xxxxxx'" );
        return;
      }

    } );

    // Теперь вычисляем и сохраняем начальный и конечный угол каждого сектора в диаграмме

    var currentPos = 0; // Текущая позиция сектора (от 0 до 1)

    for ( var slice in chartData ) {
      chartData[slice]['startAngle'] = 2 * Math.PI * currentPos;
      chartData[slice]['endAngle'] = 2 * Math.PI * ( currentPos + ( chartData[slice]['value'] / totalValue ) );
      currentPos += chartData[slice]['value'] / totalValue;
    }

    // Все готово! Теперь выводим диаграмму и добавляем обработчик события click к ней
    drawChart();
    $('#chart').click ( handleChartClick );
  }


  /**
   * Обрабатываем нажатие кнопки мыши в области диаграммы.
   *
   * Если нажатие произошло на секторе, переключаем его положение (задвинут/выдвинут).
   * Если нажатие произошло вне области диагрммы, то задвигаем все сектора на место.
   *
   * @param Event Событие click
   */

  function handleChartClick ( clickEvent ) {

    // Получаем положение курсора в момент нажатия кнопки мыши, по отношению к области рисования
    var mouseX = clickEvent.pageX - this.offsetLeft;
    var mouseY = clickEvent.pageY - this.offsetTop;

    // Кнопку мыши нажали внутри диаграммы?
    var xFromCentre = mouseX - centreX;
    var yFromCentre = mouseY - centreY;
    var distanceFromCentre = Math.sqrt( Math.pow( Math.abs( xFromCentre ), 2 ) + Math.pow( Math.abs( yFromCentre ), 2 ) );

    if ( distanceFromCentre <= chartRadius ) {

      // Да, кнопку мыши нажали внутри длиаграммы.
      // Ищем сектор, в котором была нажата кнопка мыши.
	  // Определяем угол по отношению к центру диаграммы.

      var clickAngle = Math.atan2( yFromCentre, xFromCentre ) - chartStartAngle;
      if ( clickAngle < 0 ) clickAngle = 2 * Math.PI + clickAngle;
                  
      for ( var slice in chartData ) {
        if ( clickAngle >= chartData[slice]['startAngle'] && clickAngle <= chartData[slice]['endAngle'] ) {

          // Сектор найден. Выдвигаем его или задвигаем, в соответствиис текущим положением.
          toggleSlice ( slice );
          return;
        }
      }
    }

    // Должно быть пользователь нажал кнопку мыши вне диаграммы. Нужно задвинуть все сектора на место.
    pushIn();
  }


  /**
   * Обрабатываем событие click в области таблицы.
   *
   * Возвращает номер сектора из данных jQuery, сохраненных в 
   * нажатой ячейке, затем переключаем сектор.
   *
   * @param Event Событие click
   */

  function handleTableClick ( clickEvent ) {
    var slice = $(this).data('slice');
    toggleSlice ( slice );
  }


  /**
   * Задвигаем/выдвигаем сектор.
   *
   * Если сектор выдвинут - задвигаем его. И наоборот.
   *
   * @param Number Индекс сектора (между 0 и количеством секторов - 1)
   */

  function toggleSlice ( slice ) {
    if ( slice == currentPullOutSlice ) {
      pushIn();
    } else {
      startPullOut ( slice );
    }
  }

 
  /**
   * Запускаем выдвижение сектора из диаграммы.
   *
   * @param Number Индекс сектора (между 0 и количеством секторов - 1)
   */

  function startPullOut ( slice ) {

    // Выходим, если сектор уже выдвинут
    if ( currentPullOutSlice == slice ) return;

    // Записываем сектор, который надо выдвинуть. Очищаем предыдущие анимации. Запускаем анимацию.
    currentPullOutSlice = slice;
    currentPullOutDistance = 0;
    clearInterval( animationId );
    animationId = setInterval( function() { animatePullOut( slice ); }, pullOutFrameInterval );

    // Выделяем соответствующую строку в таблице
    $('#chartData td').removeClass('highlight');
    var labelCell = $('#chartData td:eq(' + (slice*2) + ')');
    var valueCell = $('#chartData td:eq(' + (slice*2+1) + ')');
    labelCell.addClass('highlight');
    valueCell.addClass('highlight');
  }

 
  /**
   * Рисуем кадр анимации выдвижения.
   *
   * @param Number Индекс сектора, который выдвигается
   */

  function animatePullOut ( slice ) {

    // Выдвигаем сектор на шаг анимации
    currentPullOutDistance += pullOutFrameStep;

    // Если сектор выдвинут до нужного положения - заканчиваем анимацию
    if ( currentPullOutDistance >= maxPullOutDistance ) {
      clearInterval( animationId );
      return;
    }

    // Выводим кадр
    drawChart();
  }

 
  /**
   * Задвигаем выдвинутые сектора на место.
   *
   * Сбрасывает переменные анимации и перерисовывает диаграмму.
   * Также сбрасывает выделение строк в таблице.ы
   */

  function pushIn() {
    currentPullOutSlice = -1;
    currentPullOutDistance = 0;
    clearInterval( animationId );
    drawChart();
    $('#chartData td').removeClass('highlight');
  }
 
 
  /**
   * Рисуем диаграмму.
   *
   * Проходит циклом по всем секторам и рисует их.
   */

  function drawChart() {

    // Получаем контекст для рисования
    var context = canvas.getContext('2d');
        
    // Очищаем область рисования
    context.clearRect ( 0, 0, canvasWidth, canvasHeight );

    // Рисуем каждый сектор диаграммы, пропуская выдвинутый (если он есть)
    for ( var slice in chartData ) {
      if ( slice != currentPullOutSlice ) drawSlice( context, slice );
    }

    // Если есть выдвинутый сектор, рисуем его.
    // (мы рисуем выдвинутый сектор последним, таким образом его тень не будет перекрываться другими секторами.)
    if ( currentPullOutSlice != -1 ) drawSlice( context, currentPullOutSlice );
  }


  /**
   * Рисуем отдельный сектор в диаграмме.
   *
   * @param Context Контекст области рисования
   * @param Number Индекс сектора
   */

  function drawSlice ( context, slice ) {

    // Вычисляем выверенные начальный и конечный углы для сектора
    var startAngle = chartData[slice]['startAngle']  + chartStartAngle;
    var endAngle = chartData[slice]['endAngle']  + chartStartAngle;
      
    if ( slice == currentPullOutSlice ) {

      // Сектор выдвигается (или уже выдвинут).
      // Смещаем его от центра диаграммы, рисуем текстовую метку,
      // и добавляем тень.

      var midAngle = (startAngle + endAngle) / 2;
      var actualPullOutDistance = currentPullOutDistance * easeOut( currentPullOutDistance/maxPullOutDistance, .8 );
      startX = centreX + Math.cos(midAngle) * actualPullOutDistance;
      startY = centreY + Math.sin(midAngle) * actualPullOutDistance;
      context.fillStyle = 'rgb(' + chartColours[slice].join(',') + ')';
      context.textAlign = "center";
      context.font = pullOutLabelFont;
      context.fillText( chartData[slice]['label'], centreX + Math.cos(midAngle) * ( chartRadius + maxPullOutDistance + pullOutLabelPadding ), centreY + Math.sin(midAngle) * ( chartRadius + maxPullOutDistance + pullOutLabelPadding ) );
      context.font = pullOutValueFont;
      context.fillText( pullOutValuePrefix + chartData[slice]['value'] + " (" + ( parseInt( chartData[slice]['value'] / totalValue * 100 + .5 ) ) +  "%)", centreX + Math.cos(midAngle) * ( chartRadius + maxPullOutDistance + pullOutLabelPadding ), centreY + Math.sin(midAngle) * ( chartRadius + maxPullOutDistance + pullOutLabelPadding ) + 20 );
      context.shadowOffsetX = pullOutShadowOffsetX;
      context.shadowOffsetY = pullOutShadowOffsetY;
      context.shadowBlur = pullOutShadowBlur;

    } else {

      // Данный сектор не выдвинут, рисуем его от центра диаграммы
      startX = centreX;
      startY = centreY;
    }

    // Устанавливаем градиент для заполнения сектора
    var sliceGradient = context.createLinearGradient( 0, 0, canvasWidth*.75, canvasHeight*.75 );
    sliceGradient.addColorStop( 0, sliceGradientColour );
    sliceGradient.addColorStop( 1, 'rgb(' + chartColours[slice].join(',') + ')' );

    // Рисуем сектор
    context.beginPath();
    context.moveTo( startX, startY );
    context.arc( startX, startY, chartRadius, startAngle, endAngle, false );
    context.lineTo( startX, startY );
    context.closePath();
    context.fillStyle = sliceGradient;
    context.shadowColor = ( slice == currentPullOutSlice ) ? pullOutShadowColour : "rgba( 0, 0, 0, 0 )";
    context.fill();
    context.shadowColor = "rgba( 0, 0, 0, 0 )";

    // Задаем соответствующий стиль границы сектора
    if ( slice == currentPullOutSlice ) {
      context.lineWidth = pullOutBorderWidth;
      context.strokeStyle = pullOutBorderStyle;
    } else {
      context.lineWidth = sliceBorderWidth;
      context.strokeStyle = sliceBorderStyle;
    }

    // Рисуем границу сектора
    context.stroke();
  }


  /**
   * Вспомогательная функция вычисления плавности перехода
   *
   * Выглядит странно, но работает.
   *
   * @param Number Отношение текущей пройденной дистанции к максимальному расстоянию
   * @param Number Степень (чем выше число, тем плавнее переход)
   * @return Number Новое отношение
   */

  function easeOut( ratio, power ) {
    return ( Math.pow ( 1 - ratio, power ) + 1 );
  }

};

});
