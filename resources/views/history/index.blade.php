@extends('layouts.app')
@section('head')
<script type="text/javascript">  
    $(document).ready(function(){
        $('.btn-replay').on('click', function(){
            let dt = $(this).data('action');
            let tableView = generateTable(dt.number);

            $('#replayAction').html(tableView);
            autoPlay(dt.winner, dt.action);
        });
    });

    const generateTable = (number) =>{
        let htmlCol = "<div id='tableView'>";
        for(let row = 1; row <= number; row++) {
            htmlCol += "<div class='row'>";
            for(let col = 1; col <= number; col++) {
                htmlCol += "<div class='box col-auto name-"+row+"-"+col+"' data-name='"+row+"-"+col+"'></div>";
            }
            htmlCol += "</div>";
        }
        htmlCol += "</div>";
        return htmlCol;
    }

    const autoPlay = (winner, action) =>{
        let count = action.x.length + action.o.length;
        let swPlay = ['o', 'x']
        let co = 0, cx = 0;
        let click;
        for(let i=1; i<=count; i++){
            setTimeout(function timer() {
                if(swPlay[i%2]=='x'){
                    click = action.x[cx];
                    cx++;
                }
                if(swPlay[i%2]=='o'){
                    click = action.o[co];
                    co++;
                }
                $("#tableView .box.name-"+click).html(swPlay[i%2]);
            }, i * 1000);
        }
        let txt = winner+' is winner';
        if(winner=='equal'){
            txt = 'Do not have winner';
        }
        $("#tableView").append(txt);
    }
</script>
<style>
    #tableResult{
        position: relative;
    }
    #replayAction{
        display: flex;
        justify-content: center;
        text-align: center;
    }
    .overlay{
        position: absolute;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        text-align: center;
        font-size: 30px;
        color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .box{
        width: 50px;
        height: 50px;
        border: 2px solid #000;
        margin: -1px;
        font-size: 30px;
        font-weight: bold;
        text-align: center;
        vertical-align: middle;
        text-transform: uppercase;
    }
    #turnplyer{
        font-weight: bold;
        margin-bottom: 15px;
        text-transform: uppercase
    }
</style>
@endsection
@section('content')
    <div class="row g-4 justify-content-center">
        <div class="card-body">
            <div class="row">
                <table class="table">
                    <thead>
                      <tr>
                        <th class="text-center" scope="col">Datetime</th>
                        <th class="text-center" scope="col">Table</th>
                        <th class="text-center" scope="col">Winner</th>
                        <th class="text-center" scope="col"></th>
                      </tr>
                    </thead>
                    <tbody>
                      @if(count($query)>0)
                            @foreach($query as $key=>$val)
                            @php
                            $dt = $val->log ;
                            //print_r(json_decode($dt));
                            $dt = json_decode($dt);
                            @endphp
                                <tr>
                                    <td class="text-center" scope="row">{{ date('d/m/Y H:i:s',  strtotime($val->created_at)) }}</td>
                                    <td class="text-center">{{$dt->number}}x{{$dt->number}}</td>
                                    <td class="text-center">{{$dt->winner}}</td>
                                    <td class="text-center"><button class='btn btn-success btn-replay' data-bs-toggle="modal" href="#exampleModalToggle" data-action='{{ $val->log }}'>Replay</button></td>
                                </tr>
                            @endforeach
                      @endif
                    </tbody>
                  </table>
                  <div class="text-center">{!! $query->links() !!}</div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Replay</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div id="replayAction"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection
