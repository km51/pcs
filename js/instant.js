// 設備コード用
$(function () {
  searchWord = function(){
    var searchResult,
        searchText = $(this).val(), // 検索ボックスに入力された値
        targetText,
        hitNum,
        judge;

    // 検索結果を格納するための配列を用意
    searchResult = [];

    // 検索結果エリアの表示を空にする
    $('#search-result-machine__list').empty();
    $('.search-result-machine__hit-num').empty();
    $('#search-result-machine__judge').empty();

    // 検索ボックスに値が入ってる場合
    if (searchText != '') {
      $('.target-area-machine td').each(function() {
        targetText = $(this).text();

        // 検索対象となるリストに入力された文字列が存在するかどうかを判断
        if (targetText.indexOf(searchText) != -1) {
          // 存在する場合はそのリストのテキストを用意した配列に格納
          searchResult.push(targetText+";   ");
        }
      });

      // 検索結果をページに出力
      for (var i = 0; i < searchResult.length; i ++) {
        $('<span>').text(searchResult[i]).appendTo('#search-result-machine__list');
      }

      // ヒットの件数をページに出力
      if(searchResult.length==1){
        hitNum = '<span>検索結果</span>：' + searchResult.length + '件見つかりました。設備名を確認してください。'; 
        judge = '0';
      }else{
        hitNum = '<span>検索結果</span>：' + searchResult.length + '件見つかりました。<span class="huitti">設備コードを正しく入力してください。</sapn>';
        judge = '1';
      }
      $('.search-result-machine__hit-num').append(hitNum);
      document.getElementById("search-result-machine__judge").value = judge;
    }
  };
  
  // searchWordの実行
  $('#search-text-machine').on('input', searchWord);
});

// 品番コード用
$(function () {
  searchWord2 = function(){
    var searchResult,
    searchText = $(this).val(), // 検索ボックスに入力された値
    targetText,
    hitNum,
    judge;
    
    // 検索結果を格納するための配列を用意
    searchResult = [];
    
    // 検索結果エリアの表示を空にする
    $('#search-result-parts__list').empty();
    $('.search-result-parts__hit-num').empty();
    
    // 検索ボックスに値が入ってる場合
    if (searchText != '') {
      $('.target-area-parts td').each(function() {
        targetText = $(this).text();
        
        // 検索対象となるリストに入力された文字列が存在するかどうかを判断
        if (targetText.indexOf(searchText) != -1) {
          // 存在する場合はそのリストのテキストを用意した配列に格納
          searchResult.push(targetText+";   ");
        }
      });
      
      // 検索結果をページに出力
      for (var i = 0; i < searchResult.length; i ++) {
        $('<span>').text(searchResult[i]).appendTo('#search-result-parts__list');
      }
      
      // ヒットの件数をページに出力
      if(searchResult.length==1){
        hitNum = '<span>検索結果</span>：' + searchResult.length + '件見つかりました。品番を確認してください。'; 
        judge = '0';
      }else{
        hitNum = '<span>検索結果</span>：' + searchResult.length + '件見つかりました。<span class="huitti">品番コードを正しく入力してください。</sapn>';
        judge = '1';
        
      }
      $('.search-result-parts__hit-num').append(hitNum);
      document.getElementById("search-result-parts__judge").value = judge;
    }
  };
  
  // searchWordの実行
  $('#search-text-parts').on('input', searchWord2);
});





