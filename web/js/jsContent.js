/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function(){
   
    $('.myCompetitionsIntro a').click(function() {
        var input = $(this);
        if ("hidden" == $('.myCompetitionsIntro p').attr('class')) {
            $('.myCompetitionsIntro p').removeClass('hidden');
        } else {
            $('.myCompetitionsIntro p').addClass('hidden');
        }
    });
});