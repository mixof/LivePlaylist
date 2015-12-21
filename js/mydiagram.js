$(function(){
$( pieChart );

function pieChart() {

  // ������������
  var chartSizePercent = 60;                        // ������ ���������, ���������� � ��������� �� �������� ������� ���������
  var sliceBorderWidth = 1;                         // ������ (� ���������) ������� ������ ������� �������
  var sliceBorderStyle = "#fff";                    // ���� ������� ������ ������� �������
  var sliceGradientColour = "#ddd";                 // ����, ������� ������������ � ������ ����� ��������� ��� �������� ���������
  var maxPullOutDistance = 10;                      // ��������� ������ ����� ����������� ������ �� ���������
  var pullOutFrameStep = 4;                         // �� ������� �������� H������������� ������ � ������ ����� ��������
  var pullOutFrameInterval = 40;                    // ������� ms �������� ����� �������
  var pullOutLabelPadding = 50;                     // ������ ����� ���������� �������� � ��� ������
  var pullOutLabelFont = "italic 12px 'Trebuchet MS', Verdana, sans-serif";  // ����� ����� ����������� �������
  var pullOutValueFont = "bold 12px 'Trebuchet MS', Verdana, sans-serif";  // ����� �������� ����������� �������
  var pullOutValuePrefix = "";                     // ������� �������� ����������� �������
  var pullOutShadowColour = "rgba( 0, 0, 0, .5 )";  // ���� ���� ����������� �������
  var pullOutShadowOffsetX = 5;                     // �������� �� ��� X (� ��������) ���� ����������� �������
  var pullOutShadowOffsetY = 5;                     // �������� �� ��� Y (� ��������) ���� ����������� �������
  var pullOutShadowBlur = 5;                        // ��������� ������ ������� ���� ����������� �������
  var pullOutBorderWidth = 1;                       // ������ (� ��������) ������� ����������� �������
  var pullOutBorderStyle = "#333";                  // ���� ������� ����������� �������
  var chartStartAngle = -.5 * Math.PI;              // ������ ��������� �� 12 �����, � �� �� 3-�

  // ���������� ��������� ��������� ��� ���������
  var canvas;                       // ������� ��������� �� ��������
  var currentPullOutSlice = -1;     // ������, ������� �������� � ������� ������(-1 = ��� ����������� �������)
  var currentPullOutDistance = 0;   // �� ������� �������� ������ ������� ����������� ������ � ���� ��������
  var animationId = 0;              // ID ��������� ��������, ��������� � ������� setInterval()
  var chartData = [];               // ������ ��������� (�����, ��������, �����)
  var chartColours = [];            // ����� ��������� (�������� �� ������� HTML)
  var totalValue = 0;               // ����� ���� �������� � ���������
  var canvasWidth;                  // ������ ������� ���������
  var canvasHeight;                 // ������ ������� ���������
  var centreX;                      // ���������� X ������ ��������� �� ������� ���������
  var centreY;                      // ���������� Y ������ ��������� �� ������� ���������
  var chartRadius;                  // ������ ��������� � ��������

  // �������������� ������ � ������ ���������
  init();


  /**
   * ������������� ��� ��������� ������ � �����, � ����� ������������� ����������� ������� click
   * ��� ��������� � �������. ������ ���������.
   */

  function init() {

    // �������� ������� ��������� �� ��������
    canvas = document.getElementById('chart');

    // �������, ���� ������� �� ����� ����������� ��������
    if ( typeof canvas.getContext === 'undefined' ) return;

    // ������������ ��������� �������� ������� ��������� � ���������
    canvasWidth = canvas.width;
    canvasHeight = canvas.height;
    centreX = canvasWidth / 2;
    centreY = canvasHeight / 2;
    chartRadius = Math.min( canvasWidth, canvasHeight ) / 2 * ( chartSizePercent / 100 );

    // �������� ������ �� �������
    // � ������������� ����������� ������� click ��� ����� �������
    
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

      // ��������� ������ ������� � ������ � ����������� � ��� ���������� ������� click
      $(this).data( 'slice', currentRow );
      $(this).click( handleTableClick );

      // �������� � ��������� ���� ������
      if ( rgb = $(this).css('color').match( /rgb\((\d+), (\d+), (\d+)/) ) {
        chartColours[currentRow] = [ rgb[1], rgb[2], rgb[3] ];
      } else if ( hex = $(this).css('color').match(/#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/) ) {
        chartColours[currentRow] = [ parseInt(hex[1],16) ,parseInt(hex[2],16), parseInt(hex[3], 16) ];
      } else {
        alert( "������: ���� �� ����� ���� ���������! ����������, ������� ������� ������ � ������� '#xxxxxx'" );
        return;
      }

    } );

    // ������ ��������� � ��������� ��������� � �������� ���� ������� ������� � ���������

    var currentPos = 0; // ������� ������� ������� (�� 0 �� 1)

    for ( var slice in chartData ) {
      chartData[slice]['startAngle'] = 2 * Math.PI * currentPos;
      chartData[slice]['endAngle'] = 2 * Math.PI * ( currentPos + ( chartData[slice]['value'] / totalValue ) );
      currentPos += chartData[slice]['value'] / totalValue;
    }

    // ��� ������! ������ ������� ��������� � ��������� ���������� ������� click � ���
    drawChart();
    $('#chart').click ( handleChartClick );
  }


  /**
   * ������������ ������� ������ ���� � ������� ���������.
   *
   * ���� ������� ��������� �� �������, ����������� ��� ��������� (��������/��������).
   * ���� ������� ��������� ��� ������� ��������, �� ��������� ��� ������� �� �����.
   *
   * @param Event ������� click
   */

  function handleChartClick ( clickEvent ) {

    // �������� ��������� ������� � ������ ������� ������ ����, �� ��������� � ������� ���������
    var mouseX = clickEvent.pageX - this.offsetLeft;
    var mouseY = clickEvent.pageY - this.offsetTop;

    // ������ ���� ������ ������ ���������?
    var xFromCentre = mouseX - centreX;
    var yFromCentre = mouseY - centreY;
    var distanceFromCentre = Math.sqrt( Math.pow( Math.abs( xFromCentre ), 2 ) + Math.pow( Math.abs( yFromCentre ), 2 ) );

    if ( distanceFromCentre <= chartRadius ) {

      // ��, ������ ���� ������ ������ ����������.
      // ���� ������, � ������� ���� ������ ������ ����.
	  // ���������� ���� �� ��������� � ������ ���������.

      var clickAngle = Math.atan2( yFromCentre, xFromCentre ) - chartStartAngle;
      if ( clickAngle < 0 ) clickAngle = 2 * Math.PI + clickAngle;
                  
      for ( var slice in chartData ) {
        if ( clickAngle >= chartData[slice]['startAngle'] && clickAngle <= chartData[slice]['endAngle'] ) {

          // ������ ������. ��������� ��� ��� ���������, � ������������� ������� ����������.
          toggleSlice ( slice );
          return;
        }
      }
    }

    // ������ ���� ������������ ����� ������ ���� ��� ���������. ����� ��������� ��� ������� �� �����.
    pushIn();
  }


  /**
   * ������������ ������� click � ������� �������.
   *
   * ���������� ����� ������� �� ������ jQuery, ����������� � 
   * ������� ������, ����� ����������� ������.
   *
   * @param Event ������� click
   */

  function handleTableClick ( clickEvent ) {
    var slice = $(this).data('slice');
    toggleSlice ( slice );
  }


  /**
   * ���������/��������� ������.
   *
   * ���� ������ �������� - ��������� ���. � ��������.
   *
   * @param Number ������ ������� (����� 0 � ����������� �������� - 1)
   */

  function toggleSlice ( slice ) {
    if ( slice == currentPullOutSlice ) {
      pushIn();
    } else {
      startPullOut ( slice );
    }
  }

 
  /**
   * ��������� ���������� ������� �� ���������.
   *
   * @param Number ������ ������� (����� 0 � ����������� �������� - 1)
   */

  function startPullOut ( slice ) {

    // �������, ���� ������ ��� ��������
    if ( currentPullOutSlice == slice ) return;

    // ���������� ������, ������� ���� ���������. ������� ���������� ��������. ��������� ��������.
    currentPullOutSlice = slice;
    currentPullOutDistance = 0;
    clearInterval( animationId );
    animationId = setInterval( function() { animatePullOut( slice ); }, pullOutFrameInterval );

    // �������� ��������������� ������ � �������
    $('#chartData td').removeClass('highlight');
    var labelCell = $('#chartData td:eq(' + (slice*2) + ')');
    var valueCell = $('#chartData td:eq(' + (slice*2+1) + ')');
    labelCell.addClass('highlight');
    valueCell.addClass('highlight');
  }

 
  /**
   * ������ ���� �������� ����������.
   *
   * @param Number ������ �������, ������� �����������
   */

  function animatePullOut ( slice ) {

    // ��������� ������ �� ��� ��������
    currentPullOutDistance += pullOutFrameStep;

    // ���� ������ �������� �� ������� ��������� - ����������� ��������
    if ( currentPullOutDistance >= maxPullOutDistance ) {
      clearInterval( animationId );
      return;
    }

    // ������� ����
    drawChart();
  }

 
  /**
   * ��������� ���������� ������� �� �����.
   *
   * ���������� ���������� �������� � �������������� ���������.
   * ����� ���������� ��������� ����� � �������.�
   */

  function pushIn() {
    currentPullOutSlice = -1;
    currentPullOutDistance = 0;
    clearInterval( animationId );
    drawChart();
    $('#chartData td').removeClass('highlight');
  }
 
 
  /**
   * ������ ���������.
   *
   * �������� ������ �� ���� �������� � ������ ��.
   */

  function drawChart() {

    // �������� �������� ��� ���������
    var context = canvas.getContext('2d');
        
    // ������� ������� ���������
    context.clearRect ( 0, 0, canvasWidth, canvasHeight );

    // ������ ������ ������ ���������, ��������� ���������� (���� �� ����)
    for ( var slice in chartData ) {
      if ( slice != currentPullOutSlice ) drawSlice( context, slice );
    }

    // ���� ���� ���������� ������, ������ ���.
    // (�� ������ ���������� ������ ���������, ����� ������� ��� ���� �� ����� ������������� ������� ���������.)
    if ( currentPullOutSlice != -1 ) drawSlice( context, currentPullOutSlice );
  }


  /**
   * ������ ��������� ������ � ���������.
   *
   * @param Context �������� ������� ���������
   * @param Number ������ �������
   */

  function drawSlice ( context, slice ) {

    // ��������� ���������� ��������� � �������� ���� ��� �������
    var startAngle = chartData[slice]['startAngle']  + chartStartAngle;
    var endAngle = chartData[slice]['endAngle']  + chartStartAngle;
      
    if ( slice == currentPullOutSlice ) {

      // ������ ����������� (��� ��� ��������).
      // ������� ��� �� ������ ���������, ������ ��������� �����,
      // � ��������� ����.

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

      // ������ ������ �� ��������, ������ ��� �� ������ ���������
      startX = centreX;
      startY = centreY;
    }

    // ������������� �������� ��� ���������� �������
    var sliceGradient = context.createLinearGradient( 0, 0, canvasWidth*.75, canvasHeight*.75 );
    sliceGradient.addColorStop( 0, sliceGradientColour );
    sliceGradient.addColorStop( 1, 'rgb(' + chartColours[slice].join(',') + ')' );

    // ������ ������
    context.beginPath();
    context.moveTo( startX, startY );
    context.arc( startX, startY, chartRadius, startAngle, endAngle, false );
    context.lineTo( startX, startY );
    context.closePath();
    context.fillStyle = sliceGradient;
    context.shadowColor = ( slice == currentPullOutSlice ) ? pullOutShadowColour : "rgba( 0, 0, 0, 0 )";
    context.fill();
    context.shadowColor = "rgba( 0, 0, 0, 0 )";

    // ������ ��������������� ����� ������� �������
    if ( slice == currentPullOutSlice ) {
      context.lineWidth = pullOutBorderWidth;
      context.strokeStyle = pullOutBorderStyle;
    } else {
      context.lineWidth = sliceBorderWidth;
      context.strokeStyle = sliceBorderStyle;
    }

    // ������ ������� �������
    context.stroke();
  }


  /**
   * ��������������� ������� ���������� ��������� ��������
   *
   * �������� �������, �� ��������.
   *
   * @param Number ��������� ������� ���������� ��������� � ������������� ����������
   * @param Number ������� (��� ���� �����, ��� ������� �������)
   * @return Number ����� ���������
   */

  function easeOut( ratio, power ) {
    return ( Math.pow ( 1 - ratio, power ) + 1 );
  }

};

});
