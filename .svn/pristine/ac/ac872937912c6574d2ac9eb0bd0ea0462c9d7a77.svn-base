<?php
  $month_cal .= "";
  $normal_font     = "verdana";
  $normal_size     = 2;
  $normal_color    = "blue";
  $normal_bold     = 1;
  $normal_underline = 0;
  $normal_italic   = 0;

  $mini_font       = "verdana";
  $mini_size       = 1;
  $mini_color      = "black";
  $mini_bold       = 0;
  $mini_underline  = 1;
  $mini_italic     = 0;

  $table_width           = 290;
  $table_border          = 1;
  $table_background      = "#CCCCCC";
  $table_background_highlight = "#EEEEEE";
  $table_cell_spacing    = 1;
  $table_cell_padding    = 1;

  /******************************************************
    Please don't edit anything underneath this line :)
  ******************************************************/

  //For sureness that the date is stored in the var.
    $date = $HTTP_GET_VARS[ 'date' ];

  //When the date is empty get current date
    if( ! $date )  $date = date( "m-Y" ); 

  //Split up in the month and year to show
    list( $show_month,$show_year ) = explode( "-" , $date );

  //Create the date/time stamp for use
    $mktime     = mktime( 0, 0, 0, $show_month, 1, $show_year );

  //The total days of the selected month.
  //Needed for the for loop for printing the days
    $total_days = date( "t", $mktime );

  //The navigation button for the next page
    if( $show_month == 12 )
    {
      $show_month = "01";
      $show_year++;
    }  else
    {
      $show_month++;
      if( $show_month < 10 )
        $show_month = 0 . $show_month;
    }
    $next_date = $show_month . "-" . $show_year;

  //Reset the show_month and year for the previous page :þ
  list( $show_month,$show_year ) = explode( "-" , $date );

  //The navigation button for the previous page
    if( $show_month == "01" )
    {
      $show_month = "12";
      $show_year--;
    }  else
    {
      $show_month--;
      if( $show_month < 10 )
        $show_month = 0 . $show_month;
    }
    $previous_date = $show_month . "-" . $show_year;

  //Reset the show_month and year for the query_string :þ
  list( $show_month,$show_year ) = explode( "-" , $date );

  //The variable with the font settings
    $font_normal      = "<font face=\"" . $normal_font . "\" size=\"" . $normal_size . "\"
                         color=\"" . $normal_color . "\">";
    $font_mini        = "<font face=\"" . $mini_font . "\" size=\"" . $mini_size . "\"
                         color=\"" . $mini_color . "\">";

  //Check some things for the bold, underline and italic preference
    if( $normal_bold )
    {
      $font_normal     .= "<strong>";
      $font_normal_end .= "</strong>";
    }
    if( $normal_underline )
    {
      $font_normal     .= "<u>";
      $font_normal_end .= "</u>";
    }
    if( $normal_italic )
    {
      $font_normal     .= "<em>";
      $font_normal_end .= "</em>";
    }

    if( $mini_bold )
    {
      $font_mini     .= "<strong>";
      $font_mini_end .= "</strong>";
    }
    if( $mini_underline )
    {
      $font_mini     .= "<u>";
      $font_mini_end .= "</u>";
    }
    if( $mini_italic )
    {
      $font_mini     .= "<em>";
      $font_mini_end .= "</em>";
    }

  //Close the font tag's
    $font_normal_end    = "</font>";
    $font_mini_end      = "</font>";

  //The width for a cell from the table and round up
    $width_cell = ceil($table_width/7);

  $QUERY_STRING = ereg_replace("&date=".$show_month."-".$show_year,"",$_SERVER['QUERY_STRING']);

  //Print the table for output
    $month_cal .= "<table cellspacing='" . $table_cell_spacing . "' cellpadding='" . $table_cell_padding . "'
          border='" . $table_border . "' width='" . $table_width . "'>\n" .
         "  <tr>\n" .
         "    <td align=center width='" . $width_cell . "' bgcolor='" . $table_background . "'>\n". 
         "      " . $font_normal . "<a href=" . $_SERVER['PHP_SELF'] . "?" . $QUERY_STRING . "&date=" .
                $previous_date . ">&laquo;</a>\n" .
         "    </td>\n" .
         "    <td align='center' colspan='5' bgcolor='" . $table_background . "'>\n". 
         "      " . $font_normal . date( "M Y", $mktime ) . "\n".
         "    </td>\n" .
         "    <td align='center' width='" . $width_cell . "' bgcolor='" . $table_background . "'>\n". 
         "      " . $font_normal . "<a href=" . $_SERVER['PHP_SELF'] . "?" . $QUERY_STRING . "&date=" .
                $next_date . ">&raquo;</a>\n" .
         "    </td>\n" .
         "  </tr>\n" .
         "  <tr>\n" .
         "    <td width='" . $width_cell . "' align='center' bgcolor='" . $table_background . "'>" .
              $font_normal . "Sun" . "</td>\n" .
         "    <td width='" . $width_cell . "' align='center' bgcolor='" . $table_background . "'>" .
              $font_normal . "Mon" . "</td>\n" .
         "    <td width='" . $width_cell . "' align='center' bgcolor='" . $table_background . "'>" .
              $font_normal . "Tue" . "</td>\n" .
         "    <td width='" . $width_cell . "' align='center' bgcolor='" . $table_background . "'>" .
              $font_normal . "Wed" . "</td>\n" .
         "    <td width='" . $width_cell . "' align='center' bgcolor='" . $table_background . "'>" .
              $font_normal . "Thu" . "</td>\n" .
         "    <td width='" . $width_cell . "' align='center' bgcolor='" . $table_background . "'>" .
              $font_normal . "Fri" . "</td>\n" .
         "    <td width='" . $width_cell . "' align='center' bgcolor='" . $table_background . "'>" .
              $font_normal . "Sat" . "</td>\n" .
         "  </tr>\n" .
         "  <tr>\n" ;

  //Set the cell_counter at zero. This variable is used to track with the weekday
    $cell_counter = 0;

  //The for loop for printing the monthdays.
    for( $i = 1 ; $i <= 31 ; $i++ )
    {
      //cell_counter increase with one
        $cell_counter++;

      //the track for today :) contains the current weekday for keeping cell_counter original
        $day_track = $cell_counter;

      //When reaching sunday set the day_track to zero
        if( $cell_counter == 7 )  $day_track = 0;

      //Get the current weekday for the current monthday
        $week_day = date("w", mktime (0,0,0,$show_month+1,$i,$show_year) );

      //THE TD won't be closed becouse the mouseover isn't sure yet. It will be closed after the 
      //if statement.
      $month_cal .= "    <td align=\"center\" bgcolor='" . $table_background . "'";
      
      //When the week_day accords to the day_track (cell_counter) then print this day
      //will be printed into the correct cell
        if( $day_track == $week_day )
        {
          //The mouseover will be printed here
          $month_cal .= "   onmouseover=\"this.style.backgroundColor='" . $table_background_highlight . "'\"
                   onmouseout=\"this.style.backgroundColor='" . $table_background . "'\"
                   style=\"cursor: default;\">\n";
 
          //When it is the current date, the day will be bolded, but 1st make it empty
          $this_bold = "";
          $this_bold_end = "";
          if( $i."-".$show_month."-".$show_year == date("j-m-Y") )
          {
            $this_bold = "<strong>";
            $this_bold_end = "</strong>";
          }
          $month_cal .= "      " .
               $font_mini . $this_bold .
               $i .
               $this_bold_end . $font_mini_end .
               "\n" ;
        }

      //else the day have to be decreased with one day, we won't want day loss.
        else
        {
          $month_cal .= ">";
          $i--;
          $month_cal .= "&nbsp;";
        }

      $month_cal .= "    </td>\n";

      //When reaching sunday set the cell_counter to zero (after printing the day)
        if($cell_counter == 7){
          $month_cal .= "  </tr>\n" . 
               "  <tr> \n" ;
          $cell_counter = 0;
        }

      //When reaching the total days of a month, break the loop.
        if( $i == $total_days )
        {
          //BUT when there are some open spots in the table, they have to be filled
            if( $cell_counter != 0 )
              for( $cell_counter; $cell_counter <= 6; $cell_counter++)
              {
                $month_cal .= "<td align=\"center\" bgcolor='" . $table_background . "'>&nbsp;</td>";
              }
          //break; for the breaking of the loop
          break;
        }
    }

  //Print the footer of the table :þ
    $month_cal .= "  </tr>" .
         "</table>";
echo $month_cal;
?>