
 
 <div class="block-controls">
                                  <h3>Моя статистика</h3>
                                </div>
   <div class="prehead">                             
   <p class="lbn"><i class="icon-music"></i> Количество добавленных треков в компаниях</p> 
   </div>                            
<canvas id="chart" width="480" height="400"></canvas>

  <table id="chartData">
    <tr>
      <th>Компания</th><th>Кол-во mp3</th>
     </tr>
    {companyes_info}
  </table>
<div class="authors_count">
<div class="prehead">
<p class="lbn"><i class="icon-star"></i> Популярные исполнители</p>  
</div>
 <table id="chart_authors">

    <tr>
      <th>Исполнитель</th><th>Кол-во треков</th>
     </tr>
{top_artists}   

  </table>         
</div>

