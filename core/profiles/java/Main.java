////////////////////////////////////////
//to compile: Build -> Clean and build main project
//open up folder: JavaApplication6 -> build -> classes
//uploaded all files minus the folder
//open new browser window and view page
////////////////////////////////////////

//aebacb4d1558c55b4bea062b561d73bc
import java.applet.Applet;
import java.awt.*;
import java.sql.*;
import java.awt.event.*; 
import java.lang.*;
import java.awt.datatransfer.*;
import javax.swing.*;
import javax.swing.border.*;
import java.util.ArrayList;
import java.awt.dnd.*;
import java.util.Vector;
import javax.swing.border.Border;
import javax.swing.BorderFactory; 
import javax.swing.border.MatteBorder;
import javax.swing.ImageIcon;


public class Main extends JApplet  {  
    topdisplay topDisplay = new topdisplay();   //top display left side
    td topd = new td();                         //top display right side
    JTextField field;                           //error checking field... add to a panel to view if 
                                                //jdbc, database connections not working
    Statement stmt;                             //used of JDBC MYSQL statements
    String tasktypelist[];                      //the names of the task types
    int tasktypelistSize;                       //the size of the task types list
    String parentCategoryList[];                //the names of the parent categories
    int parentCategoryListIndex[];              //the corresponding code of the parent categories
    int parentCategorySize;                     //the size of the parent category list
    int total_tasks;                            //the number of days in the current template
    int current_task;
    JPanel rightMain;                           //the main panel for the middle section
    JButton insertTask;                         //insert button
    JButton printTask;                          //print button
    JButton saveTemplate;                       //save button (may remove)
    Vector tasks;                               //vector of tasks for the current day
    JTextField taskName;                        //field to enter in the created task name
    JTextField phase;                           //field to enter in the created tasks phase
    JPanel leftMain;                            //the main panel in the left section 
    Choice duration;                            //duration of the created task
    JButton hideShow;                           //hides/shows the bank(right) section
    JPanel bankMain;                            //main section for the bank (right) section
    Choice taskType;                            //the task type list box (choice box)
    ActionListener al;                          //listener for button clicks
    MouseListener ml;                           //listener for tag reminders
    MouseListener mlTasks;                      //listener for 
    MouseListener mlReminder1;                  //listener for first group of reminders
    MouseListener mlReminder2;                  //listener for second group of reminders
    MouseListener mlReminder3;                  //listener for third group of reminders
    MouseListener deleteTask;                   //listener for delete task trash can label
    MouseListener editTask;                     //listener for click task option
    JPanel gR_Panel ;                           //
    //these two lists are used to set the visibility for the individual task types or information sections
    JPanel panelInfoList[];                     //list of information panels for the task types
    JPanel panelInfoNew[];                      //create a new task panel
    JPanel panelInfoTag[];                      //tag to an existing reminder panel
    JPanel panelTaskList[];                     //list of task type panels
    Panel leftP;                                //left panel
    Choice parentCategory;                      //the parent category list box (choice box)
    JLabel lblError;                            //error label indicating faulty user values

    int parent;
    int taskTypeID;

    task_node task_node_list[];                 //master list of all the tasks in associative type
                                                //array.  accessed by task_id

    JTextField name_list[];                     //the name list for the task families
    JTextField phase_list[];                    //the phase list for the task familes
    Choice duration_list[];                     //the duration list for the task families
    Label reminder_list[];                      //the reminder list, only in three of the possible task types
    int editID;                                 //the task id of the task being edited
    boolean editFlag;                           //true if the task is being edit, false otherwise
    Color color_list[];                         //list of colors
    Font font_list[];                           //list of font types, bold, underline etc
    JPanel rightP;                              //right sub panel
    JPanel subT;                                //testing panel
    JPanel testLbl;                             //tseting label
    
    //Most parts of the code are dynamic but for every reminder than can be tagged to an existing
    //task there must be a ButtonGroup and radio button list
    ButtonGroup group;                          //group of radio buttons for General Reminders
    ButtonGroup group2;                         //group of radio buttons for Inspection Reminders
    ButtonGroup group3;                         //group of radio buttosn for Order Materials/Delivery Reminders
    int numReminders;                           //the number reminders in the list
    GridBagConstraints rGBC;                    //universal GridBagConstraint for the reminder list
    
    //these have to be seperate for the listener, so that the correct task type is linked to the reminder
    JRadioButton ButtonReminderList1[];         //radio button list for General Reminders
    JRadioButton ButtonReminderList2[];         //radio button list for Inspection Reminders
    JRadioButton ButtonReminderList3[];         //radio button list for Order Materials/Delivery Reminders
    
    JTextField bankPhase;                       //the task bank phase field
    Choice bankDuration;                        //the duration drop down menu in the task bank section
    JButton bankInsertBtn;                      //insert task bank button
    JList primaryTaskList;                      //primary tasks list
    JList secondaryTaskList;                    //secondar tasks list
    String id_hash;                             //id_hash from the html code 
    String profile_id;                          //profile_id from the html code
    String nameBank[];                          //the list of names of task bank tasks from the database 
    JLabel nameBankPrimary[];                   //the list of primary task bank names
    JLabel nameBankSecondary[];                 //the list of reminder tasks bank names
    String taskIDBank[];                        //the list of task id's of task bank tasks from the database
    String taskIDBankPrimary[];                 //the list of primary task id's
    String taskIDBankSecondary[];               //the list of reminder task id's
    int secondary;                              //the number of reminder tasks
    int primary;                                //the number of primary tasks
    int taskBankSize;                           //the total number of task bank tasks
    int taskBankTasks[];                        //
    int taskBankTasksSize;                      //
    JTextField primarySearch;                   //the primary search box
    JTextField secondarySearch;                 //secondary serach box
    ScrollPane spBank;                          //scroll pane for the primary list of tasks
    ScrollPane spBank1;                         //scroll pane for the reminder tasks
    Popup popupAdd;                             //popup for the plus
    Popup popupDelete;                          //popup for the minus
    TextField popupFieldAdd;                    //text field for the add popup
    JButton popupButtonAdd;                     //button for the add popup
    
  
///////////////////////////
// This class holds all the information for a task.
///////////////////////////
   public class task_node {
       int task_id;
       
       String task_name;
       int task_phase;
       int task_duration;
       int task_bank;
       boolean begin_duration;
       int task_tag;
       int day_duration;
       //sets the id, name, phase, duration and bank variable for this particular node
       public void add(int id, String name, int phase, int duration, int bank){
           task_id = id;
           task_name = name;
           task_phase = phase;
           task_duration = duration;
           task_bank = bank;
           begin_duration = false;
           task_tag = -1;
       }
       public task_node(){
           begin_duration = false;
           task_tag = -1;
       }
       public void setTag(int taskid, int tag){
           task_tag = tag;
           task_id = taskid;
       }
       public void setBegin(boolean b){
           begin_duration = b;
       }
       public boolean isBegin(){
           return begin_duration;
       }
       public int getPhase(){
           return task_phase;
       }
       public int getDuration(){
           return task_duration;
       }
       public String getName(){
           return task_name;
       }
       public void setName(String s){
           task_name = s;
       }
       public void setDay(int i){
           day_duration = i;
       }
       public int getDay(){
           return day_duration;
       }
       public int getBank(){
           return task_bank;
       }
       public int getTaskID(){
           return task_id;
       }
       public int getTaskType(){
           return(task_id/10000);
       }
       public int getTag(){
           return task_tag;
       }
   }
//initilization function
    public void init()   
    {       
        id_hash = getParameter("id_hash");           //gets info from the html page
        profile_id = getParameter("profile_id"); 
        task_node_list = new task_node[100000];
        for (int i = 0; i < 100000; i++)
            task_node_list[i] = null;
        init_first();                               //reads data from the database
        numReminders = 1;
        ButtonReminderList1 = new JRadioButton[1000];
        ButtonReminderList2 = new JRadioButton[1000];
        ButtonReminderList3 = new JRadioButton[1000];
        editID = -1;
        color_list = new Color[tasktypelistSize];
        color_list[0] = new Color(0,0,0);
        color_list[1] = new Color(0,0,0);
        color_list[2] = new Color(184,138,0);
        color_list[3] = new Color(0,46,184);
        color_list[4] = new Color(0,46,184);
        color_list[5] = new Color(0,184,46);
        color_list[6] = new Color(245,184,0);
        color_list[7] = new Color(184,138,0);
        color_list[8] = new Color(255,102,51);
        //color_list[9] = new Color(0,153,153);
        font_list = new Font[tasktypelistSize];
        for (int d = 1; d < 9; d++)
                    font_list[d] = new Font(getFont().getFontName(), Font.PLAIN, getFont().getSize()); 
        font_list[0] = new Font(getFont().getFontName(), Font.BOLD, getFont().getSize());
        font_list[3] = new Font(getFont().getFontName(), Font.BOLD, getFont().getSize());
  
        
        panelInfoList = new JPanel[100];
        panelTaskList = new JPanel[100];
        panelInfoNew = new JPanel[100];
        panelInfoTag = new JPanel[100];
        tasks = new Vector();
        for (int i = 0; i < 100; i++) {
            Vector tmp = new Vector();
            tmp.add("");
            tasks.add(tmp);
        }
      
        JPanel bankP;
        leftP = new Panel();
        leftP.setLayout(new BorderLayout());
        rightP = new JPanel();
        GridBagConstraints rightPGBC = new GridBagConstraints();
        bankP = new JPanel();   
        TextField top = new TextField("Insert New Task Box",40);
        top.setBackground(Color.blue);
        al = new MyActionListener(); 
        ml = new MyMouseListener();
        mlReminder1 = new MyMouseListenerReminder1();
        mlReminder2 = new MyMouseListenerReminder2();
        mlReminder3 = new MyMouseListenerReminder3();
        
        mlTasks = new MyMouseListenerTasks();
        //right side
        JPanel buttonPanel; 
        buttonPanel = new JPanel();
        insertTask = new JButton("Insert Task");
        insertTask.setActionCommand("insert");
        insertTask.addActionListener(al);
        printTask = new JButton("Print Task");
        saveTemplate = new JButton("Clear Fields");
        saveTemplate.setActionCommand("clear");
        saveTemplate.addActionListener(al);
        buttonPanel.setLayout(new GridLayout(1,3));
        insertTask.setSize(50,25);
        printTask.setSize(50,25);
        saveTemplate.setSize(50,25);
        insertTask.setFont(new Font("Helvetica",Font.BOLD,10));
        printTask.setFont(new Font("Helvetica",Font.BOLD,10));
        saveTemplate.setFont(new Font("Helvetica",Font.BOLD,10));
        buttonPanel.add(insertTask);
        buttonPanel.add(printTask);
        buttonPanel.add(saveTemplate);
        taskName = new JTextField("", 15);
        phase = new JTextField("", 4); 
        JLabel tn = new JLabel("Task Name:"); 
        tn.setSize(250,tn.getHeight());
        JLabel p = new JLabel("Phase: ");
        JLabel t = new JLabel("Task Type: ");
        JLabel d = new JLabel("Duration:");
        JLabel pC = new JLabel("Parent Category:");
        p.setSize(100,p.getHeight());
        t.setSize(100,t.getHeight());
        d.setSize(100,d.getHeight());
        JLabel topLabel = new JLabel();
        topLabel.setFont(new Font("Helvetica",Font.BOLD,12));
        topLabel.setForeground(Color.white);
        topLabel.setText("Insert New Task Box"); 
        topLabel.setBackground(Color.blue);
        parentCategory = new Choice();
        taskType = new Choice();
        taskType.addItemListener(new MyChoiceListener());
        int i;
        for (i = 0; i < tasktypelistSize; i++)
            taskType.add(tasktypelist[i]);
        for (i = 0; i < parentCategorySize; i++)
            parentCategory.add(parentCategoryList[i]);
        duration = new Choice();
        for (int j = 1; j <15; j++)
            duration.add(Integer.toString(j));
        
        //main right panel, task information
        rightMain = new JPanel();  
        rightMain.setLayout(new GridLayout(4,2));
        taskName.setSize(100,taskName.getHeight());
        phase.setSize(100,phase.getHeight());
        duration.setSize(100,duration.getHeight());
        taskType.setSize(100,taskType.getHeight());       
        rightMain.setLayout(new GridBagLayout());
        rightMain.setMaximumSize(new Dimension(250,300));
        GridBagConstraints c = new GridBagConstraints();
        c.fill = GridBagConstraints.HORIZONTAL;     
        c.insets = new Insets(1,3,1,3);
        c.gridx = 0;  c.gridy = 0;
        rightMain.add(tn,c);
        c.gridx = 1;  c.gridy = 0;
        rightMain.add(taskName,c);
        c.gridx = 0;  c.gridy = 1;
        rightMain.add(p,c);
        c.gridx = 1;  c.gridy = 1;
        rightMain.add(phase,c);
        c.gridx = 0;  c.gridy = 2;
        rightMain.add(d,c);
        c.gridx = 1;  c.gridy = 2;
        rightMain.add(duration,c);
        c.gridx = 0;  c.gridy = 3;
        rightMain.add(t,c);
        c.gridx = 1;  c.gridy = 3;
        rightMain.add(taskType,c);
        c.gridx = 0;  c.gridy = 4;
        rightMain.add(pC,c);
        c.gridx = 1;  c.gridy = 4;
        rightMain.add(parentCategory,c);
        Panel tmpP = new Panel();
        tmpP.setBackground(Color.blue);
        Label topLa = new Label();
        topLa.setBounds(0,0,250,20);
        topLa.setFont(new Font("Helvetica",Font.BOLD,12));
        topLa.setForeground(Color.blue);
        topLa.setText("My Task Template");     
        topLa.setSize(200,10);
        rightPGBC.gridx = 0; 
        rightPGBC.gridy = 0;
        rightP.add(topLa,rightPGBC);
        buttonPanel.setSize(200,50);
        rightMain.setBorder(BorderFactory.createEtchedBorder() );
        rightPGBC.gridx = 0; 
        rightPGBC.gridy = 1;
        rightP.add(buttonPanel,rightPGBC);
        rightMain.setSize(250,300);
        rightPGBC.gridx = 0; 
        rightPGBC.gridy = 2;
        rightP.add(rightMain,rightPGBC);
        lblError = new JLabel();
        lblError.setVisible(false);
        lblError.setForeground(Color.red);
        rightPGBC.gridx = 0; 
        rightPGBC.gridy = 3;
        rightP.add(lblError,rightPGBC);
        subT = new JPanel();
        subT.setSize(200,300);
        subT.setLayout(new GridBagLayout());
        GridBagConstraints cSub = new GridBagConstraints();
        GridBagConstraints cSub1 = new GridBagConstraints();
        cSub.anchor = GridBagConstraints.WEST;
        cSub1.anchor = GridBagConstraints.WEST;
        cSub.ipady = 5;
 
        //cSub1.fill = GridBagConstraints.HORIZONTAL;
        cSub1.ipady = 5;
        cSub1.anchor = GridBagConstraints.WEST;
        name_list = new JTextField[tasktypelistSize];
        phase_list = new JTextField[tasktypelistSize];
        duration_list = new Choice[tasktypelistSize];
        reminder_list = new Label[tasktypelistSize];
        for (i = 0; i < tasktypelistSize; i++) {
         
            JPanel tmpPanel = new JPanel();
            tmpPanel.setLayout(new GridBagLayout());
            cSub.anchor = GridBagConstraints.WEST;
            JLabel tmpLbl = new JLabel(tasktypelist[i]);
            tmpLbl.setPreferredSize(new  Dimension(270,20));
            tmpLbl.setForeground(color_list[i]);
            tmpLbl.setFont(font_list[i]);
            tmpLbl.addMouseListener(mlTasks);
            /* tmpBtn.setActionCommand(tasktypelist[i]);
            tmpBtn.addActionListener(al);*/
           // tmpBtn.setSize(200,50);
            cSub.gridx = 0;
            cSub.gridy = i*2;
            tmpPanel.add(tmpLbl,cSub);
            JPanel tmpSubPanel = new JPanel();
            tmpSubPanel.setLayout(new GridBagLayout());
            GridBagConstraints cTmpSubPanel = new GridBagConstraints();
            cTmpSubPanel.anchor = GridBagConstraints.WEST;
            cTmpSubPanel.gridx = 0;
           
            cTmpSubPanel.gridy = i*3;
            
            int tmpy = cSub.gridy;
            cSub.gridy = i*3;
            //tag reminder box
            if (((i == 1) || (i == 4) || (i == 7))){
                Label tagReminders = new Label("Tag To Existing Reminder");
                tagReminders.addMouseListener(ml);
                //tmpPanel.add(tagReminders,cSub);
                reminder_list[i] = tagReminders;
                Integer tmpI = new Integer(i);
                reminder_list[i].setName(tmpI.toString());
                tmpSubPanel.add(tagReminders,cSub);
                cTmpSubPanel.gridx = 0;
                cTmpSubPanel.gridy = i*3+1;
            }
            
     

           
            panelInfoNew[i] = subTasks(i);
            panelInfoTag[i] = new JPanel();
            panelInfoTag[i].setBackground(color_list[i]);
            panelInfoTag[i].setBorder(BorderFactory.createLineBorder(Color.black));
            panelInfoTag[i].setLayout(new GridBagLayout());
            rGBC = new GridBagConstraints();
            rGBC.anchor = GridBagConstraints.WEST;
            //JScrollPane jsp = new JScrollPane();
            //jsp.add(panelInfoTag[i]);
            tmpSubPanel.add(panelInfoNew[i],cTmpSubPanel);
              cTmpSubPanel.gridx = 0;
                cTmpSubPanel.gridy = i*3+2;
                cSub.gridy = i*3+2;
            tmpSubPanel.add(panelInfoTag[i],cSub);
            
            panelInfoTag[i].setVisible(false);

           // panelInfoList[i] = subTasks(i);
            panelInfoList[i]=tmpSubPanel;
            cSub.gridx = 0;
            cSub.gridy = i*2+1;
            tmpPanel.add(panelInfoList[i],cSub);
            panelTaskList[i] = tmpPanel;
            cSub1.gridx = 0;
            cSub1.gridy = i;
            subT.add(panelTaskList[i],cSub1);    
            panelInfoList[i].setVisible(false);
        }
        panelTaskList[0].setVisible(false);
        ScrollPane spSubT = 
            new ScrollPane(ScrollPane.SCROLLBARS_AS_NEEDED);  
        // spSubT.setBorder(nul); 
        Border emptyBorder = BorderFactory.createEmptyBorder();
        // spSubT.setViewportBorder(emptyBorder );
        //spSubT.setLayout(new GridBagLayout());
        
        GridBagConstraints gbcss = new GridBagConstraints();
        gbcss.anchor = GridBagConstraints.FIRST_LINE_START;
        gbcss.gridx = 0;
        gbcss.gridy = 0;
        
        spSubT.add(subT,gbcss);
        spSubT.setSize(300,300);
        rightPGBC.gridx = 0; 
        rightPGBC.gridy = 4;
        rightPGBC.fill = GridBagConstraints.BOTH;
        rightPGBC.ipady = 10;
        rightP.add(spSubT,rightPGBC);
        
        //left side 
        current_task = 1;
       
        field = new JTextField();    
        field.setEditable(false);
        leftMain = new JPanel();
        refresh_leftMain();
        ScrollPane sp = 
            new ScrollPane(ScrollPane.SCROLLBARS_AS_NEEDED); 
        sp.add(leftMain); 
        leftP.add(sp, BorderLayout.CENTER);
        leftP.add(topDisplay, BorderLayout.NORTH);
        
        //bank panel
        bankP.setLayout(new GridBagLayout());
        GridBagConstraints bankPGBC = new GridBagConstraints();
        bankPGBC.fill = GridBagConstraints.VERTICAL;
         bankPGBC.gridx = 0; bankPGBC.gridy = 0;
        JPanel topPanel = new JPanel();
        Label topL = new Label();
        topL.setFont(new Font("Helvetica",Font.BOLD,12));
        topL.setForeground(Color.blue);
        topL.setText("My Task Bank");
        topPanel.add(topL);
        topPanel.setBorder(BorderFactory.createMatteBorder(0,0,1,0,Color.black));
        bankPGBC.anchor = GridBagConstraints.NORTH;
        bankPGBC.fill = GridBagConstraints.HORIZONTAL;
        bankP.add(topPanel, bankPGBC);
       
        bankMain = new JPanel(); 
        bankMain.setLayout(new GridBagLayout());
        GridBagConstraints bankMainGBC = new GridBagConstraints();
         //   JScrollPane scrollPane = new JScrollPane();
         //scrollPane.getViewport().setView(dataList);
        secondary = 0;
        primary = 0;
        taskIDBankPrimary = new String[taskBankSize];
        taskIDBankSecondary = new String[taskBankSize];
        nameBankPrimary = new JLabel[taskBankSize];
        nameBankSecondary = new JLabel[taskBankSize];
        for (int n = 0; n < taskBankSize; n++){
            Integer tmpN = new Integer(taskIDBank[n]);
            int tmpType = tmpN.intValue();
            tmpType = tmpType/10000;
            if ((tmpType == 2) || (tmpType ==5) ||(tmpType ==7)){
                taskIDBankSecondary[secondary] = new String();
                taskIDBankSecondary[secondary] = taskIDBank[n];
                nameBankSecondary[secondary] = new JLabel(nameBank[n]);
                
                for (int j = 0; j < taskBankTasksSize; j++){
                    if (tmpN.intValue() == taskBankTasks[j]){
                        nameBankSecondary[secondary].setEnabled(false);
                    }
                }
                secondary++;
                
            }
            else {
                taskIDBankPrimary[primary] = new String();
                taskIDBankPrimary[primary] = taskIDBank[n];
                nameBankPrimary[primary] = new JLabel(nameBank[n]);
                 for (int j = 0; j < taskBankTasksSize; j++){
                    if (tmpN.intValue() == taskBankTasks[j]){
                        nameBankPrimary[primary].setEnabled(false);
                    }
                }
                primary++;
               
            }


        }
        nameBankSecondary = (JLabel[]) resizeArray (nameBankSecondary, secondary); 
        nameBankPrimary = (JLabel[]) resizeArray (nameBankPrimary, primary); 
        taskIDBankPrimary = (String[]) resizeArray (taskIDBankPrimary, primary);
        taskIDBankSecondary = (String[]) resizeArray (taskIDBankSecondary, secondary);        
        primaryTaskList = new JList(nameBankPrimary);
        primaryTaskList.setCellRenderer(new MyCellRenderer());
       
        secondaryTaskList = new JList(nameBankSecondary);
        secondaryTaskList.setCellRenderer(new MyCellRenderer());
        MouseListener mouseListener1 = new MouseAdapter() {
        public void mouseClicked(MouseEvent e) {
            secondaryTaskList.clearSelection();
            }
        };
        primaryTaskList.addMouseListener(mouseListener1);
        MouseListener mouseListener2 = new MouseAdapter() {
        public void mouseClicked(MouseEvent e) {
            primaryTaskList.clearSelection();
            }
        };
        secondaryTaskList.addMouseListener(mouseListener2);
       
        primarySearch = new JTextField(7);
        
        primarySearch.addKeyListener (
          new KeyAdapter () {
            public void keyReleased (KeyEvent e) {
              adjustPosition (nameBankPrimary, spBank, primarySearch.getText(), primary);
            }
          } // end anonymous class
        ); // end method call
        secondarySearch = new JTextField(7);
        secondarySearch.addKeyListener (
          new KeyAdapter () {
            public void keyReleased (KeyEvent e) {
              adjustPosition (nameBankSecondary, spBank1, secondarySearch.getText(), secondary);
            }
          } // end anonymous class
        ); // end method call
        spBank = new ScrollPane();
        spBank.setSize(200,175);
        spBank.add(primaryTaskList);
        Label tx = new Label();
        tx.setFont(new Font("Helvetica",Font.BOLD,12));
        tx.setForeground(Color.blue);
        tx.setText("Primary Tasks");
        Panel tmpPanel = new Panel();
        tmpPanel.add(tx);
        tmpPanel.add(primarySearch);
        bankMainGBC.gridx = 0; bankMainGBC.gridy=0;
        //bankMain.add(tx, bankMainGBC);
        bankMainGBC.gridx = 0; bankMainGBC.gridy=0;
        bankMain.add(tmpPanel, bankMainGBC);
        bankMainGBC.gridx = 0; bankMainGBC.gridy=1;
        bankMain.add(spBank, bankMainGBC);
        
        bankMainGBC.gridx = 0; bankMainGBC.gridy=2;
        Label txx = new Label();
        txx.setFont(new Font("Helvetica",Font.BOLD,12));
        txx.setForeground(Color.blue);
        Panel tmpPanel1 = new Panel();
        tmpPanel1.add(txx);
        tmpPanel1.add(secondarySearch);
        txx.setText("Reminder Tasks");
        bankMain.add(tmpPanel1, bankMainGBC);
        spBank1 = new ScrollPane();
        spBank1.setSize(200,175);
        spBank1.add(secondaryTaskList);
         bankMainGBC.gridx = 0; bankMainGBC.gridy=3;
        bankMain.add(spBank1,bankMainGBC);
        bankPGBC.gridx = 0;
        bankPGBC.gridy = 1;

        bankP.add(bankMain, bankMainGBC); 
        
        JPanel bankInsert = new JPanel();
        JLabel bpLabel = new JLabel("Phase: ");
        bankPhase = new JTextField("", 4);
        JLabel bdLabel = new JLabel("Duration: ");
        bankDuration = new Choice();
        for (int j = 1; j <15; j++)
            bankDuration.add(Integer.toString(j));
        bankInsertBtn = new JButton("Insert");
        bankInsertBtn.setActionCommand("insertBank");
        bankInsertBtn.addActionListener(al);
        bankInsert.setLayout(new GridBagLayout());
        GridBagConstraints biGBC = new GridBagConstraints();
        biGBC.anchor = GridBagConstraints.EAST;
        biGBC.gridx = 0; biGBC.gridy = 0;
        bankInsert.add(bpLabel, biGBC);
        biGBC.gridx = 1; biGBC.gridy = 0;
        bankInsert.add(bankPhase, biGBC);
        biGBC.gridx = 0; biGBC.gridy = 1;
        bankInsert.add(bdLabel, biGBC);
        biGBC.gridx = 1; biGBC.gridy = 1;
        bankInsert.add(bankDuration, biGBC);
        biGBC.gridx = 2; biGBC.gridy = 1;
        bankInsert.add(bankInsertBtn, biGBC);
         bankPGBC.gridx = 0; bankPGBC.gridy = 5;
        bankPGBC.anchor = GridBagConstraints.SOUTH;
        bankP.add(bankInsert, bankPGBC);
        setLayout(new BorderLayout());
        Panel te = new Panel();
        add(leftP,BorderLayout.WEST);
        add(rightP,BorderLayout.CENTER);
        add(bankP,BorderLayout.EAST);
        rightP.setBorder(BorderFactory.createMatteBorder(1,1,1,0,Color.black));
        bankP.setBorder(BorderFactory.createLineBorder(Color.black));
        //digitally sign and add jar file to the applet tag as archive   ImageIcon cup = new ImageIcon("http://www.selectionsheet.com/images/button_drop.gif");
        group = new ButtonGroup();
        group2 = new ButtonGroup();
        group3 = new ButtonGroup();
        numReminders = 0;
        //initlize the reminders
        //first button is invisble
        JRadioButton rb1 = new JRadioButton("none");
        rb1.setVisible(false);
        rb1.setName("none");
        rb1.setSelected(true);
        JRadioButton rb2 = new JRadioButton("none");
        rb2.setVisible(false);
        rb2.setName("none");
        rb2.setSelected(true);
        JRadioButton rb3 = new JRadioButton("none");
        rb3.setVisible(false);
        rb3.setName("none");
        rb3.setSelected(true);
        group.add(rb1);
        group2.add(rb2);
        group3.add(rb3);
        rGBC.anchor = GridBagConstraints.WEST;
        rGBC.gridx = 0;
        rGBC.gridy = numReminders;
        rGBC.insets = new Insets(3,3,3,3);
        rGBC.ipadx = 2;
        rGBC.ipady = 2;
        ButtonReminderList1[numReminders] = rb1;
        ButtonReminderList2[numReminders] = rb2;
        ButtonReminderList3[numReminders] = rb3;
        JLabel lbl1 = new JLabel("Reset");
        lbl1.addMouseListener(mlReminder1);
        JLabel lbl2 = new JLabel("Reset");
        lbl2.addMouseListener(mlReminder2);
        JLabel lbl3 = new JLabel("Reset");
        lbl3.addMouseListener(mlReminder3);
        panelInfoTag[1].add(lbl1);
        panelInfoTag[1].add(rb1,rGBC);
        panelInfoTag[4].add(lbl2);
        panelInfoTag[4].add(rb2,rGBC);
        panelInfoTag[7].add(lbl3);
        panelInfoTag[7].add(rb3,rGBC);
        numReminders++;
        for (int current_id = 10000; current_id<100000; current_id++){
            if (task_node_list[current_id]!= null)
                if ((task_node_list[current_id].getTaskType() == 2) ||
                        (task_node_list[current_id].getTaskType() == 5) ||
                        (task_node_list[current_id].getTaskType() == 8))
                   tagReminders(task_node_list[current_id]);
        }

    }
     class MyCellRenderer extends JLabel implements ListCellRenderer {

     public Component getListCellRendererComponent(
       JList list,
       Object value,            // value to display
       int index,               // cell index
       boolean isSelected,      // is the cell selected
       boolean cellHasFocus)    // the list and the cell have the focus
     {
         JLabel t = (JLabel)value;
         setText(t.getText());
         if (isSelected) {
             setBackground(list.getSelectionBackground());
             setForeground(list.getSelectionForeground());
           }
         else {
               setBackground(list.getBackground());
               setForeground(list.getForeground());
           }

           if (t.isEnabled() == false){
               setBackground(Color.gray);
               setForeground(Color.lightGray);
             
           }


           
        setOpaque(true);

         return this;
     }
 }
    public void adjustPosition(JLabel []list, ScrollPane bank, String search, int size){
       
        int x = getPosition(list, search, size);
        
        bank.setScrollPosition(0,16*x);
    }

    public int getPosition(JLabel []list, String search, int size){
        int index = 1;
        for (int j = 0; j < search.length(); j++){
            String s = search.substring(0,(j+1));
            for (int i = 0; i < size; i++){
                String tmp = list[i].getText();
                tmp = tmp.toLowerCase();
                s = s.toLowerCase();
                if (tmp.startsWith(s)){
                        index = i;
                        break;
                }
         }
        }
        return index;
    }
    private  Object resizeArray (Object oldArray, int newSize) {
       int oldSize = java.lang.reflect.Array.getLength(oldArray);
       Class elementType = oldArray.getClass().getComponentType();
       Object newArray = java.lang.reflect.Array.newInstance(
             elementType,newSize);
       int preserveLength = Math.min(oldSize,newSize);
       if (preserveLength > 0)
          System.arraycopy (oldArray,0,newArray,0,preserveLength);
       return newArray; 
   }
   
    public JPanel subTasks(int index) {
        
        //two panels
        //
        //tag to task_id
        //
        //
        //2 5 8
        gR_Panel = new JPanel();
        gR_Panel.setLayout(new GridBagLayout());       
        GridBagConstraints cGR = new GridBagConstraints();
         cGR.gridx = 1; cGR.gridy = 0;

        //name
        cGR.anchor = GridBagConstraints.EAST;
        JLabel gR_Name = new JLabel("Name: ");
        gR_Name.setSize(50,gR_Name.getHeight());
         cGR.gridx = 0; cGR.gridy = 1;
        gR_Panel.add(gR_Name,cGR);
        JTextField gR_NameField = new JTextField("",15);
        gR_NameField.setSize(50,gR_NameField.getHeight());
         cGR.gridx = 1; cGR.gridy = 1;
        gR_Panel.add(gR_NameField,cGR);
        //phase
        JLabel gR_Phase = new JLabel("Phase: ");
        gR_Phase.setSize(50,gR_Phase.getHeight());
         cGR.gridx = 0; cGR.gridy = 2;
        gR_Panel.add(gR_Phase,cGR);
        JTextField gR_PhaseField = new JTextField("",4);
        gR_PhaseField.setSize(50,gR_PhaseField.getHeight());
         cGR.gridx = 1; cGR.gridy = 2;
        gR_Panel.add(gR_PhaseField,cGR);
        //duration
        JLabel gR_Duration = new JLabel("Duration: ");
        gR_Duration.setSize(50,gR_Duration.getHeight());
         cGR.gridx = 0; cGR.gridy = 3;
        gR_Panel.add(gR_Duration,cGR);
        Choice durationBox = new Choice();
        for (int j = 1; j <15; j++)
            durationBox.add(Integer.toString(j));
         durationBox.setSize(50,durationBox.getHeight());
          cGR.gridx = 1; cGR.gridy = 3;
        gR_Panel.add(durationBox,cGR);
        
        gR_Panel.setVisible(true);
        gR_Panel.setBorder(BorderFactory.createLineBorder(Color.black));
        gR_Panel.setBackground(color_list[index]);
        name_list[index] = gR_NameField;
        phase_list[index] = gR_PhaseField;
        duration_list[index] = durationBox;
        return gR_Panel;
    }
    ///////////////
    //compares passed in string with the values inside the validValues string
    //if any of the characters are not valid, false will be returned otherwise true
    //////////////
    public boolean errorCheckString(String tmp) {
        String validValues = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890&_-() ";
        for (int i = 0; i < (tmp.length()-1); i++) {
            String tmpS = tmp.substring(i, i+1);
            if (validValues.indexOf(tmpS)== -1)
                return true;
                       
        }   
        return false;
    }
    public int searchVector(Vector x, task_node n){
        for (int z = 0; z < total_tasks; z++){
            Vector tmp = (Vector)x.elementAt(z);
            if (tmp.contains(n)){
                return z;
            }
        }
        
       return -1; 
    }
    
    public void refresh_leftMain(){
        int ii;
  
       
        int total_task_length = total_tasks;
        Vector tTasks = new Vector();
        for (int z = 0; z < 100; z++){
            Vector tm = new Vector();
            tTasks.add(tm);
        }
        
        for (int j = 10000; j < 100000; j++){
           if (task_node_list[j] != null){
               int k;
               for (k = 0; k < task_node_list[j].getDuration(); k++){
                    Vector tmp = new Vector();
                    tmp = (Vector)tTasks.elementAt(task_node_list[j].getPhase()+k);
                    task_node n = new task_node();
                    n = task_node_list[j];
                    n.setDay(k);
                    tmp.add(n);
                    tTasks.set(n.getPhase()+k, tmp);
                    
                }
           }
       }
        
        int i;
        leftMain.removeAll();
        leftMain.setLayout(new GridBagLayout());
        GridBagConstraints c = new GridBagConstraints();
       
        
    for (i =0; i < total_tasks; i++){
            c.fill = GridBagConstraints.BOTH;
            c.anchor = GridBagConstraints.WEST;     
            DefaultListModel listModel = new DefaultListModel();
           
            c.gridx = 0;
            c.gridy = i;
            //c.ipadx = 75;
            c.weightx = 2;
            //c.anchor = GridBagConstraints.CENTER;
       
            ImageIcon iconPlus = createImageIcon("plus.GIF","plus");
            JLabel plus = new JLabel(iconPlus);
            MyMouseListenerPlus plusL = new MyMouseListenerPlus();
            plus.addMouseListener(plusL);
            Integer tInteger = new Integer(i);
            plus.setName(tInteger.toString());
            ImageIcon iconMinus = createImageIcon("minus.GIF","minus");
            JLabel minus = new JLabel(iconMinus);
            MyMouseListenerMinus minusL = new MyMouseListenerMinus();
            minus.addMouseListener(minusL);
            minus.setName(tInteger.toString());
            JLabel days = new JLabel(Integer.toString(i+1));
            
            //c.fill = GridBagConstraints.BOTH;
            JPanel dayPanel = new JPanel();
            dayPanel.setBorder(BorderFactory.createMatteBorder(0,0,1,0,Color.black));
            dayPanel.setLayout(new BorderLayout());
            dayPanel.add(days, BorderLayout.NORTH);
            dayPanel.add(plus, BorderLayout.WEST);
            dayPanel.add(minus, BorderLayout.EAST);
         
            dayPanel.setBackground(Color.white);
            leftMain.add(dayPanel,c);
            Vector tmp;
            c.gridx = 1;
            
            JPanel taskBox = new JPanel();
            taskBox.setLayout(new GridBagLayout());
            GridBagConstraints tb = new GridBagConstraints();
            GridBagConstraints taskGBC = new GridBagConstraints();
  
            taskGBC.anchor = GridBagConstraints.WEST;
       
            tb.anchor = GridBagConstraints.WEST;
            tb.gridx = 0;
            JPanel testing = new JPanel(); 
            if (tTasks.elementAt(i) != null){
                
                tmp = (Vector)tTasks.elementAt(i);
                //taskBox.setLayout(new GridLayout(tmp.size(),1));
                task_node tmpNode = new task_node();
                int j;
                c.gridx = 0;
                for ( j = 0; j< tmp.size(); j++){
                    tb.gridy = j;
                    c.gridy = j;
                    tmpNode = (task_node)tmp.elementAt(j);
                    //tmpNode.getName();
                    //tmpNode.getTaskID();
                    JPanel indv = new JPanel();
                    JLabel tmpLabel = new JLabel("<html><u>"+tmpNode.getName()+"</u>");
                    tmpLabel.setForeground(color_list[(tmpNode.getTaskType()-1)]);
                    tmpLabel.setFont(font_list[(tmpNode.getTaskType()-1)]);
                    indv.add(tmpLabel,taskGBC);

                    ImageIcon icon = createImageIcon("trash.gif","trash");
                    JLabel tl = new JLabel(icon);

                    JButton x = new JButton(icon);    
                    x.setOpaque(true);
                    Integer tx = new Integer(tmpNode.getTaskID());
                    x.setActionCommand("e"+tx.toString());
                    x.addActionListener(al);
                    JButton y = new JButton("delete");
                    y.setActionCommand("d"+tx.toString());
                    y.addActionListener(al);
                    tl.setName(tx.toString());
                    deleteTask = new MyMouseListenerDelete();
                    tl.addMouseListener(deleteTask);
                    tmpLabel.setName(tx.toString());
                    editTask = new MyMouseListenerEdit();
                    tmpLabel.addMouseListener(editTask);
                    
                    tb.anchor = GridBagConstraints.WEST;
                    //indv.setBorder(BorderFactory.createMatteBorder(1,1,1,1,Color.blue));
                    tmpLabel.add(tl);
                    JPanel tmpPanel = new JPanel();
                    tmpPanel.setLayout(new FlowLayout(FlowLayout.LEFT));
                    GridBagConstraints ttt = new GridBagConstraints();
                    ttt.fill = GridBagConstraints.BOTH;
                    ttt.anchor = GridBagConstraints.WEST;
                    ttt.gridx = 0;
                    ttt.gridy = 0;
                    
                    ttt.gridx = 1;
                    if ((tmpNode.getPhase() == i) && (tmpNode.getName() != null)){
                        
                        tmpPanel.add(tmpLabel);
                        if (tmpNode.getDuration() > 1)
                        tmpPanel.add(new JLabel("(1 of "+tmpNode.getDuration()+")"));
                        tmpPanel.add(tl);
                    }
                    else{
                         ImageIcon icons = createImageIcon("tree.gif","tree");
                         
                         tmpPanel.add(new JLabel(icons));
                         Integer tI = new Integer (j);
                         Integer tD = new Integer (tmpNode.getDuration());
                         int start = searchVector(tTasks,tmpNode);
                         tmpPanel.add(new JLabel(tmpNode.getName()+" ("+(i-start+1)+" of "+""+tD.toString()+")"));
                    }
                       
                    
                    tmpPanel.setBackground(Color.white);
                    taskBox.setLayout(new GridLayout(tmp.size(),1));
                    taskBox.add(tmpPanel);
                    //taskBox.add(tl);
                    c.gridx = 0;
                   
                    c.gridx = 0; 
                    c.anchor = GridBagConstraints.WEST;                 
                    c.gridx = 0;
                    //listModel.addElement(tmp.elementAt(j));
                }
            }

            c.weightx = 15;
            c.ipadx = 25;
            c.gridx = 1;
            c.gridy = i;
            //c.anchor = GridBagConstraints.WEST;
            //list.setBorder(BorderFactory.createMatteBorder(0,1,1,0,Color.black));
            //leftMain.add(list,c);
            taskBox.setBorder(BorderFactory.createMatteBorder(0,1,1,0,Color.black));
            c.anchor = GridBagConstraints.WEST;
            c.fill = GridBagConstraints.BOTH;
            //c.insets = new Insets(0,0,0,15);
            JLabel tl = new JLabel("test");
            GridBagConstraints testingG = new GridBagConstraints();
            testingG.anchor = GridBagConstraints.WEST;
            testing.add(tl,c);
            testing.setBorder(BorderFactory.createMatteBorder(0,1,1,0,Color.black));
            leftMain.add(taskBox,c);
            taskBox.setBackground(Color.white);
            leftMain.setBackground(Color.white);
        }
    }
        protected static ImageIcon createImageIcon(String path,
                                               String description) {
        java.net.URL imgURL = Main.class.getResource(path);
        if (imgURL != null) {
            return new ImageIcon(imgURL, description);
        } else {
            System.err.println("Couldn't find file: " + path);
            return null;
        }
    }
    public void start()
    {   
          topDisplay.repaint();
    }
    public void tagReminders(task_node node){
        JRadioButton rb1 = new JRadioButton(node.getName());
        JRadioButton rb2 = new JRadioButton(node.getName());
        JRadioButton rb3 = new JRadioButton(node.getName());
        rb1.setBackground(color_list[1]);
        rb2.setBackground(color_list[4]);
        rb3.setBackground(color_list[7]);
        Integer t = new Integer(node.getTaskID());
        rb1.setName(t.toString());
        group.add(rb1);
        group2.add(rb2);
        group3.add(rb3);
        rGBC.anchor = GridBagConstraints.WEST;
        rGBC.gridx = 0;
        rGBC.gridy = numReminders;
        rGBC.insets = new Insets(3,3,3,3);
        rGBC.ipadx = 2;
        rGBC.ipady = 2;
        ButtonReminderList1[numReminders] = rb1;
        ButtonReminderList2[numReminders] = rb2;
        ButtonReminderList3[numReminders] = rb3;
        panelInfoTag[1].add(rb1,rGBC);
        panelInfoTag[4].add(rb2,rGBC);
        panelInfoTag[7].add(rb3,rGBC);
        numReminders++;
    }
    public void init_first() {
         total_tasks = 100;
          try  {  
            Class.forName("com.mysql.jdbc.Driver");			
            Connection con = DriverManager.getConnection("jdbc:mysql://209.9.228.34:3306/selectionsheet_beta","ss_client","4600powder_tb");			
            stmt = con.createStatement();			
            ResultSet rs = stmt.executeQuery("SELECT * FROM task_type");			
            tasktypelist = new String[100];
            int x= 0;
            while (rs.next())			
            {				
                String name = rs.getString("name");				
                tasktypelist[x] = name; 
                x++;
            }	
            tasktypelistSize = x;
            rs = stmt.executeQuery("SELECT * FROM category ORDER BY name ASC");
            x =0;
            parentCategoryList = new String[1000];
            parentCategoryListIndex = new int[1000];
            taskBankTasks = new int[1000];
            taskBankTasksSize = 0;
            while (rs.next())
            {
                String name = rs.getString("name");
                parentCategoryList[x] = name;
                Integer index = new Integer(rs.getString("code"));
                parentCategoryListIndex[x] = index.intValue();
                x++;
            }
            parentCategorySize = x;   
            rs = stmt.executeQuery("SELECT task, name FROM task_library " +
                    "               WHERE id_hash = '"+id_hash+"' "+
                    "               ORDER BY name ASC");		
            x =0;
            rs.last();
            nameBank = new String[rs.getRow()];
            taskIDBank = new String[rs.getRow()];
            
            rs.beforeFirst();
            while (rs.next())
            {
                nameBank[x] = rs.getString("name");
                taskIDBank[x] = rs.getString("task");
                x++;
            }
            taskBankSize = x;
            rs = stmt.executeQuery("SELECT task_id, task_name, task_phase, task_duration, task_tag, task_bank " +
                    "               FROM template_builder_tasks " +
                    "               WHERE id_hash = '"+id_hash+"'");
            while (rs.next()){
                Integer task_idtmp = new Integer(rs.getString("task_id"));
                int task_id = task_idtmp.intValue();
                task_node_list[task_id] = new task_node();
                Integer tmpD = new Integer(rs.getString("task_duration"));
                
                Integer tmpP = new Integer(rs.getString("task_phase"));
                int tmpPInt = tmpP.intValue() - 1;
                int tmpBInt = 1;
                if (rs.getString("task_bank")!=null){                    
                    Integer tmpB = new Integer(rs.getString("task_bank"));
                    tmpBInt = tmpB.intValue();
                    taskBankTasks[taskBankTasksSize] = tmpBInt;
                    taskBankTasksSize++;
                }
                task_node_list[task_id].add(task_id, rs.getString("task_name"), tmpPInt, tmpD.intValue(),tmpBInt);
                
          
                if (rs.getString("task_tag")!=null){
                    Integer tag = new Integer(rs.getString("task_tag"));
                    task_node_list[task_id].setTag(task_id, tag.intValue());
                }                      
            }
        }		
        catch(Exception e)		
        {			
            field.setText("error: " + e.getMessage());		
        }       
    }
    private class DragMouseAdapter extends MouseAdapter {
        public void mousePressed(MouseEvent e) {
            JComponent c = (JComponent)e.getSource();
            TransferHandler handler = c.getTransferHandler();
            handler.exportAsDrag(c, e, TransferHandler.COPY);
        }
    }
   
    class td extends Canvas { 
        public td() {
            setSize(250,20);
        }
        public void paint(Graphics g) {
          g.setFont(new Font("Helvetica",Font.BOLD,12));
          g.setColor(Color.blue);
          g.fillRect(0,0,250,20);
          g.setColor(Color.white);
          g.drawString(
            "Insert New Task Box", 3, 15);
          
        }
    }
    class topdisplay extends Canvas {
          public topdisplay() {  
              setBackground(Color.white);
    setSize(310, 40); 
  } 
        public void paint(Graphics g) {
          g.setFont(new Font("Helvetica",Font.BOLD,12));
          g.drawString(
            "New Task Template", 3, 15);
          g.drawString(
            "Day", 4, 35);
          g.drawString(
            "Task Name", 55, 35);
            g.setColor(Color.gray);
          g.drawLine(0,20,350,20);
          g.drawLine(0,40,350,40);
          g.drawLine(48,40,48,20);
          g.drawLine(0,0,350,0);
          g.drawLine(0,0,0,40);
          //g.drawLine(249,0,249,40);
          g.drawLine(0,40,249,40);
        }
    }


    class MyActionListener implements ActionListener { 
      public void actionPerformed(ActionEvent e) { 
        // Action Command is not necessarily label 
       
        String s = e.getActionCommand(); 
        boolean breakError = false;
        lblError.setVisible(false);
        //edit or delete a task
        for (int k = 10000; k < 100000; k++){
            Integer j = new Integer(k);
            String val = j.toString();       
            if (s.indexOf(val)!=-1){
                //parent task
                Integer pt = new Integer(val.substring(1,3));
                int parent_task = pt.intValue();
                //child task
                String xx = new String(String.valueOf((char)val.charAt(0)));
                pt = new Integer(xx);
                int family_task = pt.intValue();
                //delete
                //k = task_id
                
                if (s.indexOf("d") != -1){
                   
                    if ((task_node_list[k].getTaskType() == 2) ||
                        (task_node_list[k].getTaskType() == 5) ||
                        (task_node_list[k].getTaskType() == 8)){
                      
                        for (int q = 1; q < numReminders; q++){
                           
                            Integer tmpInt = new Integer(ButtonReminderList1[q].getName());
                            if (tmpInt.intValue() == k){
                                panelInfoTag[1].remove(ButtonReminderList1[q]);
                                panelInfoTag[4].remove(ButtonReminderList2[q]);
                                panelInfoTag[7].remove(ButtonReminderList3[q]);
                                for (int r = q; r < numReminders; r++){
                                    ButtonReminderList1[r] = ButtonReminderList1[r+1];
                                    ButtonReminderList2[r] = ButtonReminderList2[r+1];
                                    ButtonReminderList3[r] = ButtonReminderList3[r+1];
                                }
                                numReminders--;
                                rightP.validate();
                            }   
                        }
                    }

                    task_node_list[k] = null;
                    clearFields();
                    refresh_leftMain();
                    leftMain.validate();
                    leftP.validate();
                    rightP.validate();
                }
                //EDIT
                //k = task_id
                if (s.indexOf("e") != -1){
                    editFlag = true;
                    editID = k;
                    Integer tt = new Integer(parent_task);
                    
                    task_node tmp;
                    tmp = task_node_list[k];
                    Integer t = new Integer((tmp.getPhase()+1));
                    phase.setText(t.toString());
                    taskName.setText(tmp.getName());
                    if (tmp.getBank() > 1)
                        taskName.disable();
                    else
                        taskName.enable();
                    //taskName.setText(tt.toString());
                    duration.select((tmp.getDuration()-1));
                    for (int i = 0; i < tasktypelistSize; i++){
                        panelTaskList[i].setVisible(true);
                    }
                    //family_task--;
                    for (int i = 0; i < parentCategorySize; i++){
                        if (parentCategoryListIndex[i] == parent_task)
                            parentCategory.select(i); 
                    }
                    family_task--;
                    taskType.select(family_task);
                    panelTaskList[family_task].setVisible(false);
                    parent_task--;
                    //parentCategory.select(parentCategoryListIndex[parent_task]);
                    //find family tasks
                    int tmpTask_id = k;
                    tmpTask_id += 10000;
        
                    while (tmpTask_id != k) {
                        if (tmpTask_id >= 100000)
                            tmpTask_id -= 100000;
                        String ss= taskName.getText();
                        taskName.setText(ss);
                        if (task_node_list[tmpTask_id] != null) {
                            int index = tmpTask_id / 10000;   
                            index--;
                            panelInfoList[index].setVisible(true);
                            if (task_node_list[tmpTask_id].getTag() != -1){
                                panelInfoTag[index].setVisible(true);
                                panelInfoNew[index].setVisible(false);
                                for (int q = 1; 1 < tasktypelistSize; q++){
                                    Integer tmpN = new Integer(ButtonReminderList1[q].getName());
                                    if (tmpN.intValue() == task_node_list[tmpTask_id].getTag())
                                        ButtonReminderList1[q].setSelected(true);
                                    tmpN = new Integer(ButtonReminderList2[q].getName());
                                    if (tmpN.intValue() == task_node_list[tmpTask_id].getTag())
                                        ButtonReminderList2[q].setSelected(true);
                                    tmpN = new Integer(ButtonReminderList3[q].getName());
                                    if (tmpN.intValue() == task_node_list[tmpTask_id].getTag())
                                        ButtonReminderList3[q].setSelected(true);
                                }
                            } else{                               
                                panelInfoTag[index].setVisible(false);
                                panelInfoNew[index].setVisible(true);
                                name_list[index].setText(task_node_list[tmpTask_id].getName());
                                phase_list[index].setText((String.valueOf(task_node_list[tmpTask_id].getPhase()+1)));
                                duration_list[index].select((task_node_list[tmpTask_id].getDuration()-1));
                            }
                        }     
                        tmpTask_id += 10000;
                    }   
                }
            }
        }

        if (s.equals("insert")) { 
          
          Integer x = new Integer(phase.getText());
          int phaseVal = x.intValue();
          phaseVal--;
          if (phaseVal < 0 || phaseVal > total_tasks) {
              lblError.setText("Please enter a phase between 1 and "+total_tasks);
              lblError.setVisible(true);
              breakError = true;
          }
        
          int t = duration.getSelectedIndex(); //gets the index of the selected value
          t++;                                 //add one because the first value is 1, index = 0
          if ((phaseVal + t) > total_tasks) {
              lblError.setText("The duration must fall within the total days");
              lblError.setVisible(true);
              breakError = true;
          }
          if (x == null){
              lblError.setText("Please enter a phase");
              lblError.setVisible(true);
              breakError = true;
          }
          
            Vector tmp;
          tmp = (Vector)tasks.elementAt(phaseVal);
          String taskNameEnter = taskName.getText();
          tmp.add(taskName.getText());
          if (taskNameEnter == "") {
              lblError.setText("Please enter a task name");
              lblError.setVisible(true);
              breakError = true;
          }
          if (errorCheckString(taskNameEnter) == true) {
              lblError.setText("Invalid character in the name field");
              lblError.setVisible(true);
              breakError = true;
          }
          
          if (breakError == false){ 
              
              for (int i = 0; i < t; i++)
                tasks.set((phaseVal+i),tmp);
             
              //task_id
              //task type parent child
              // 0         00     00
              int task_id = 10000;

              taskTypeID = taskType.getSelectedIndex() + 1;
              parent = parentCategoryListIndex[parentCategory.getSelectedIndex()];
              task_id = 10000 * (taskType.getSelectedIndex()+1);
              task_id += 100 * ( parentCategoryListIndex[parentCategory.getSelectedIndex() ] );
              int test_id = task_id;
              int test_id_family = test_id/10000;
              test_id = test_id - (test_id_family * 10000);
              test_id += 10000;
              boolean testFlag = true;
              while (testFlag == true){
                  testFlag = false;
                  while (test_id < 100000){
                      if (task_node_list[test_id] != null){
                        task_id++;
                        testFlag = true;
                        break;
                      }
                      test_id+=10000;                     
                  }
                  test_id = task_id;
                  test_id_family = test_id/10000;
                  test_id = test_id - (test_id_family * 10000);
                  test_id += 10000;
              }
              if (editID != -1){
                task_id = editID;
              }    
              task_node_list[task_id] = new task_node();
              task_node_list[task_id].add(task_id, taskNameEnter, (phaseVal), t, 1);        
              int child = task_id - (10000 * (taskType.getSelectedIndex()+1)) - 
                      (100 * ( parent));
              if (!(task_node_list[task_id].getTaskType() == 1) ||
                      (task_node_list[task_id].getTaskType() == 4) ||
                      (task_node_list[task_id].getTaskType() == 7))
                tagReminders(task_node_list[task_id]);
              
              try{
                stmt.executeUpdate("INSERT INTO template_builder_tasks (`id_hash` , `profile_id` , `task_id` , " +
                      "`task_name` , `task_phase` , `task_duration`) VALUES ('"+id_hash+"', '"+profile_id+"', '"+task_id+"', " +
                      "'"+taskNameEnter+"' , '"+(phaseVal+1)+"' ,'"+t+"') ");	
                }   
              catch(Exception ee){		
                  lblError.setText("NO INTERNET CONNECTION");
                  lblError.setVisible(true);
                  breakError = true;
                  field.setText("error: " + ee.getMessage());		
              }  
              for (int j = 0; j < tasktypelistSize; j++){
                  if (panelInfoNew[j].isVisible() == true){


                    String tmpName = name_list[j].getText();
                    if (tmpName.length() != 0) {
                        Integer tmpP = new Integer(phase_list[j].getText());
                        task_id = 10000 * (j+1);
                        task_id += 100 * parent;
                        task_id += child;
                        task_node_list[task_id] = new task_node();
                        task_node_list[task_id].add(task_id, name_list[j].getText(), (tmpP.intValue()-1), (duration_list[j].getSelectedIndex()+1),1);
                        tagReminders(task_node_list[task_id]);
                       try{
                            stmt.executeUpdate("INSERT INTO template_builder_tasks (`id_hash` , `profile_id` , `task_id` , " +
                            "`task_name` , `task_phase` , `task_duration`) VALUES ('"+id_hash+"', '"+profile_id+"', '"+task_id+"', " +
                            "'"+tmpName+"' , '"+(tmpP.intValue())+"' ,'"+(duration_list[j].getSelectedIndex()+1)+"') ");	
                        }   
                        catch(Exception ee) {		
                          lblError.setText("NO INTERNET CONNECTION");
                          lblError.setVisible(true);
                          breakError = true;
                          field.setText("error: " + ee.getMessage());		
                        }  
                    }
                  }else{
                    //tag reminder
                    for (int n = 0; n < numReminders; n++){
                        if (ButtonReminderList1[n].isSelected()){
                           if (n!=0){
                                task_id = 10000 * (n);
                                task_id += 100 * parent;
                                task_id += child;
                                Integer tmpInt = new Integer(ButtonReminderList1[n].getName());
                                int task_tag = tmpInt.intValue();
                                task_node_list[task_id] = new task_node();
                                task_node_list[task_id].setTag(task_id, task_tag);
                        
                             try{
                            stmt.executeUpdate("INSERT INTO template_builder_tasks (`id_hash` , `profile_id` , `task_id` , " +
                            "`task_tag`) VALUES ('"+id_hash+"', '"+profile_id+"', '"+task_id+"', " +
                            "'"+task_tag+"') ");	
                                }   
                                catch(Exception ee) {		
                                  lblError.setText("NO INTERNET CONNECTION");
                                  lblError.setVisible(true);
                                  breakError = true;
                                  field.setText("error: " + ee.getMessage());		
                                }      
                            }
                        }
                        if (ButtonReminderList2[n].isSelected()){
                           if (n!=0){
                                task_id = 10000 * (n);
                                task_id += 100 * parent;
                                task_id += child;
                                Integer tmpInt = new Integer(ButtonReminderList2[n].getName());
                                int task_tag = tmpInt.intValue();
                                task_node_list[task_id] = new task_node();
                                task_node_list[task_id].setTag(task_id, task_tag);
                        
                             try{
                            stmt.executeUpdate("INSERT INTO template_builder_tasks (`id_hash` , `profile_id` , `task_id` , " +
                            "`task_tag`) VALUES ('"+id_hash+"', '"+profile_id+"', '"+task_id+"', " +
                            "'"+task_tag+"') ");	
                                }   
                                catch(Exception ee) {		
                                  lblError.setText("NO INTERNET CONNECTION");
                                  lblError.setVisible(true);
                                  breakError = true;
                                  field.setText("error: " + ee.getMessage());		
                                }      
                            }
                        }
                        if (ButtonReminderList3[n].isSelected()){
                           if (n!=0){
                                task_id = 10000 * (n);
                                task_id += 100 * parent;
                                task_id += child;
                                Integer tmpInt = new Integer(ButtonReminderList3[n].getName());
                                int task_tag = tmpInt.intValue();
                                task_node_list[task_id] = new task_node();
                                task_node_list[task_id].setTag(task_id, task_tag);
                        
                             try{
                            stmt.executeUpdate("INSERT INTO template_builder_tasks (`id_hash` , `profile_id` , `task_id` , " +
                            "`task_tag`) VALUES ('"+id_hash+"', '"+profile_id+"', '"+task_id+"', " +
                            "'"+task_tag+"') ");	
                                }   
                                catch(Exception ee) {		
                                  lblError.setText("NO INTERNET CONNECTION");
                                  lblError.setVisible(true);
                                  breakError = true;
                                  field.setText("error: " + ee.getMessage());		
                                }      
                            }
                        }
                    }  
                  }
              }
              editID = -1;
              
              refresh_leftMain();
              leftMain.validate();
              leftP.validate();
              int dd;
              dd = 10;
            // if (myTaskList[parent] == null)
            // dd = myTaskList[parent].add(1, 1, 2, "bob", 1, 2, 10);
            //dd = myTaskList[parent].add(parent, -1, taskTypeID, taskNameEnter, (phaseVal+1), t, 10);
            //int nt_id = myTaskList[parent].add(parent, -1, taskTypeID, taskNameEnter, (phaseVal+1), t, 10);
             /* try{
                stmt.executeUpdate("INSERT INTO template_builder_tasks (`id_hash` , `profile_id` , `task_id` , " +
                      "`task_name` , `task_phase` , `task_duration`) VALUES ('"+id_hash+"', '"+profile_id+"', '"+task_id+"', " +
                      "'"+taskNameEnter+"' , '"+(phaseVal+1)+"' ,'"+t+"') ");	
                }   
                 catch(Exception ee)		
                {		
                  lblError.setText("NO INTERNET CONNECTION");
                  lblError.setVisible(true);
                  breakError = true;
                  field.setText("error: " + ee.getMessage());		
                }  
           */
              phase.setText("");
              taskName.setText("");
              taskName.enable();
              duration.select(0);
              parentCategory.select(0);
              for (int k = 0; k < tasktypelistSize; k++){
                  name_list[k].setText("");
                  phase_list[k].setText("");
                  duration.select(0);
                  panelInfoList[k].setVisible(false);
                  
              }
              ButtonReminderList1[0].setSelected(true);
              ButtonReminderList2[0].setSelected(true);
              ButtonReminderList3[0].setSelected(true);
            }
           
        } 
        int i;
        for (i = 0; i < tasktypelistSize; i++) {
            if (s.equals(tasktypelist[i])) {
                if (panelInfoList[i].isVisible() == true)
                    panelInfoList[i].setVisible(false);
                else
                    panelInfoList[i].setVisible(true);
                }
        }
        if (s.equals("clear")) {
            clearFields();
        }
        if (s.equals("insertBank")){
            Integer tmpP = new Integer(bankPhase.getText());
            int index = primaryTaskList.getSelectedIndex();
            JLabel tmp = (JLabel)primaryTaskList.getSelectedValue();
                            String name;
                Integer tmpID; 
                if (index != -1){
                    tmpID = new Integer(taskIDBankPrimary[index]);
                    name = nameBankPrimary[index].getText();

                }
                else {
                    index = secondaryTaskList.getSelectedIndex();
                    tmpID = new Integer(taskIDBankSecondary[index]);
                    name = nameBankSecondary[index].getText();

                    tmp = (JLabel)secondaryTaskList.getSelectedValue();
                }
                 if (tmp.isEnabled() == true) {
               // taskName.setText()
                int tmpD = bankDuration.getSelectedIndex();
                tmpD++;

                tmp.setEnabled(false);
                int task_id = tmpID.intValue();
                //strip off last two values and then find next avaliable node
                task_node_list[task_id] = new task_node();
                task_node_list[task_id].add(task_id, name, (tmpP.intValue()-1), tmpD, tmpID.intValue());

                try{
                    stmt.executeUpdate("INSERT INTO template_builder_tasks (`id_hash` , `profile_id` , `task_id` , " +
                    "`task_name` , `task_phase` , `task_duration`, `task_bank`) VALUES ('"+id_hash+"', '"+profile_id+"', '"+task_id+"', " +
                    "'"+nameBank[index]+"' , '"+tmpP.intValue()+"' ,'"+tmpD+"' , '"+tmpID.intValue()+"') ");	
                }   
                catch(Exception ee) {		
                  lblError.setText("NO INTERNET CONNECTION");
                  lblError.setVisible(true);
                  breakError = true;
                  field.setText("error: " + ee.getMessage());		
                }  
                refresh_leftMain();
                leftMain.validate();
                leftP.validate();
                bankPhase.setText("");
                bankDuration.select(0);
                // data[index] = node
            }
        }
      }
      public void clearFields() {
            editID = -1;
            parentCategory.enable(true);
            taskType.enable(true);
            //clear new box
            for (int i = 0; i < tasktypelistSize; i++)  {
                panelInfoList[i].setVisible(false);
                name_list[i].setText("");
                phase_list[i].setText("");
                duration_list[i].select(0);   
            }
            //clear reminder box
            ButtonReminderList1[0].setSelected(true);
            ButtonReminderList2[0].setSelected(true);
            ButtonReminderList3[0].setSelected(true);
            phase.setText("");
            taskName.setText("");
            duration.select(0);
            taskName.enable();
            taskType.select(0);
            parentCategory.select(0);
            editFlag = false;
      }
       public void tagReminders(task_node node){
            JRadioButton rb1 = new JRadioButton(node.getName());
            JRadioButton rb2 = new JRadioButton(node.getName());
            JRadioButton rb3 = new JRadioButton(node.getName());
            rb1.setBackground(color_list[1]);
            rb2.setBackground(color_list[4]);
            rb3.setBackground(color_list[7]);
            Integer t = new Integer(node.getTaskID());
            rb1.setName(t.toString());
            group.add(rb1);
            group2.add(rb2);
            group3.add(rb3);
            rGBC.anchor = GridBagConstraints.WEST;
            rGBC.gridx = 0;
            rGBC.gridy = numReminders;
            rGBC.insets = new Insets(3,3,3,3);
            rGBC.ipadx = 2;
            rGBC.ipady = 2;
            ButtonReminderList1[numReminders] = rb1;
            ButtonReminderList2[numReminders] = rb2;
            ButtonReminderList3[numReminders] = rb3;
            panelInfoTag[1].add(rb1,rGBC);
            panelInfoTag[4].add(rb2,rGBC);
            panelInfoTag[7].add(rb3,rGBC);
            numReminders++;
       }
    }
    
    class MyMouseListenerTasks implements MouseListener {
        public void mousePressed(MouseEvent e) {     
        }

        public void mouseReleased(MouseEvent e) {
        }

        public void mouseEntered(MouseEvent e) {
            setCursor(Cursor.getPredefinedCursor(Cursor.HAND_CURSOR));
        }

        public void mouseExited(MouseEvent e) {
            setCursor(Cursor.getPredefinedCursor(Cursor.DEFAULT_CURSOR));
        }

        
        public void mouseClicked(MouseEvent e) {
           JLabel l = (JLabel)e.getComponent();
           for (int i = 0; i < tasktypelistSize; i++) {
            if (l.getText() == (tasktypelist[i])) {
                if (panelInfoList[i].isVisible() == true)
                    panelInfoList[i].setVisible(false);
                else
                    panelInfoList[i].setVisible(true);
                }
            }
        }
        

        void saySomething(String eventDescription, MouseEvent e) {

        }

    }
    class MyMouseListenerReminder1 implements MouseListener {
        public void mousePressed(MouseEvent e) {     
        }

        public void mouseReleased(MouseEvent e) {
        }

        public void mouseEntered(MouseEvent e) {
            setCursor(Cursor.getPredefinedCursor(Cursor.HAND_CURSOR));
        }

        public void mouseExited(MouseEvent e) {
            setCursor(Cursor.getPredefinedCursor(Cursor.DEFAULT_CURSOR));
        }     
        public void mouseClicked(MouseEvent e) {
             ButtonReminderList1[0].setSelected(true);
        }

        void saySomething(String eventDescription, MouseEvent e) {

        }

    }
    class MyMouseListenerReminder2 implements MouseListener {
        public void mousePressed(MouseEvent e) {    
        }

        public void mouseReleased(MouseEvent e) {
        }

        public void mouseEntered(MouseEvent e) {
            setCursor(Cursor.getPredefinedCursor(Cursor.HAND_CURSOR));
        }

        public void mouseExited(MouseEvent e) {
            setCursor(Cursor.getPredefinedCursor(Cursor.DEFAULT_CURSOR));
        }

        
        public void mouseClicked(MouseEvent e) {
             ButtonReminderList2[0].setSelected(true);
        }

        void saySomething(String eventDescription, MouseEvent e) {

        }

    }
    class MyMouseListenerReminder3 implements MouseListener {
        public void mousePressed(MouseEvent e) {    
        }

        public void mouseReleased(MouseEvent e) {
        }

        public void mouseEntered(MouseEvent e) {
            setCursor(Cursor.getPredefinedCursor(Cursor.HAND_CURSOR));
        }

        public void mouseExited(MouseEvent e) {
            setCursor(Cursor.getPredefinedCursor(Cursor.DEFAULT_CURSOR));
        }
        public void mouseClicked(MouseEvent e) {
             ButtonReminderList3[0].setSelected(true);
        }

        void saySomething(String eventDescription, MouseEvent e) {

        }

    }
    class MyMouseListenerDelete implements MouseListener {
        public void mousePressed(MouseEvent e) {
     
        }

        public void mouseReleased(MouseEvent e) {

        }

        public void mouseEntered(MouseEvent e) {
            setCursor(Cursor.getPredefinedCursor(Cursor.HAND_CURSOR));
        }

        public void mouseExited(MouseEvent e) {
            setCursor(Cursor.getPredefinedCursor(Cursor.DEFAULT_CURSOR));
        }

        
        public void mouseClicked(MouseEvent e) {
            setCursor(Cursor.getPredefinedCursor(Cursor.DEFAULT_CURSOR));
            JLabel l = (JLabel)e.getComponent();
            Integer id = new Integer(l.getName());
            int k = id.intValue();
            if ((task_node_list[k].getTaskType() == 2) ||
                        (task_node_list[k].getTaskType() == 5) ||
                        (task_node_list[k].getTaskType() == 8)){
                      
                        for (int q = 1; q < numReminders; q++){
                           
                            Integer tmpInt = new Integer(ButtonReminderList1[q].getName());
                            if (tmpInt.intValue() == k){
                                panelInfoTag[1].remove(ButtonReminderList1[q]);
                                panelInfoTag[4].remove(ButtonReminderList2[q]);
                                panelInfoTag[7].remove(ButtonReminderList3[q]);
                                for (int r = q; r < numReminders; r++){
                                    ButtonReminderList1[r] = ButtonReminderList1[r+1];
                                    ButtonReminderList2[r] = ButtonReminderList2[r+1];
                                    ButtonReminderList3[r] = ButtonReminderList3[r+1];
                                }
                                numReminders--;
                                
                                rightP.validate();
                                validate();
                            }
                                
                        }
                    }
                        if (task_node_list[k].getBank() > 1){
                        //disable taskbankitem
                        
                         for (int n = 0; n < secondary; n++){
                            int tmpType = task_node_list[k].getTaskType();
                            if ((tmpType == 2) || (tmpType ==5) ||(tmpType ==7)){  
                                
                                    Integer vals = new Integer(taskIDBankSecondary[n]);
                                    if (k == vals.intValue()){
                                        nameBankSecondary[n].setEnabled(true);
                                        secondaryTaskList.setSelectedIndex(n);
                                        secondaryTaskList.clearSelection();
                                        secondaryTaskList.repaint();
                                        
                                    }
                               
                            }
                         }
                         for (int n = 0; n < primary; n++){
                            int tmpType = task_node_list[k].getTaskType();
                            if (!((tmpType == 2) || (tmpType ==5) ||(tmpType ==7))){  
                               
                                    Integer vals = new Integer(taskIDBankPrimary[n]);
                                    if (k == vals.intValue()){
                                       // nameBankPrimary[n].setEnabled(true);
                                        nameBankPrimary[n].setEnabled(true);
                                        primaryTaskList.setSelectedIndex(n);
                                        primaryTaskList.clearSelection();
                                        primaryTaskList.repaint();
                                    }               
                            }
                         }
                    }
                    task_node_list[k] = null;
                    clearFields();
                    refresh_leftMain();
                    leftMain.validate();
                    leftP.validate();
                    rightP.validate();
                    validate();
                    spBank.repaint();
                    spBank1.repaint();


        }
        public void clearFields() {
            editID = -1;
            //clear new box
            for (int i = 0; i < tasktypelistSize; i++)  {
                panelInfoList[i].setVisible(false);
                name_list[i].setText("");
                phase_list[i].setText("");
                duration_list[i].select(0);
                 
            }
            //clear reminder box
            ButtonReminderList1[0].setSelected(true);
            ButtonReminderList2[0].setSelected(true);
            ButtonReminderList3[0].setSelected(true);
            phase.setText("");
            taskName.setText("");
            duration.select(0);
            taskName.enable();
            taskType.select(0);
            parentCategory.select(0);
            editFlag = false;
      }
        void saySomething(String eventDescription, MouseEvent e) {

        }

    }

    class MyMouseListenerMinus implements MouseListener {
        public void mousePressed(MouseEvent e) {  
        }
        public void mouseReleased(MouseEvent e) {
        }

        public void mouseEntered(MouseEvent e) {
            setCursor(Cursor.getPredefinedCursor(Cursor.HAND_CURSOR));
        }

        public void mouseExited(MouseEvent e) {
            setCursor(Cursor.getPredefinedCursor(Cursor.DEFAULT_CURSOR));
        }

        
        public void mouseClicked(MouseEvent e) {
            setCursor(Cursor.getPredefinedCursor(Cursor.DEFAULT_CURSOR));
            JLabel l = (JLabel)e.getComponent();
            final JPanel addPanel = new JPanel();
            addPanel.add(new JLabel("How many rows do you want to remove?"));
            popupButtonAdd = new JButton("Remove");
            addPanel.add(popupFieldAdd);
            Choice c = new Choice();
            for (int j = 1; j <15; j++)
                c.add(Integer.toString(j));
           // popupButtonAdd.setActionCommand("popupAdd");
           // popupButtonAdd.addActionListener(al);
            addPanel.add(c);
            popupButtonAdd.addActionListener( new ActionListener(){
                public void actionPerformed(ActionEvent e){
                    //adjustRows();
                   popupAdd.hide();
                }
           }
        );
   
        addPanel.add(popupButtonAdd);
        //addPanel.setBorder(BorderFactory.createLineBorder(Color.black));
        PopupFactory factory = PopupFactory.getSharedInstance();

        // MouseInfo mi = new MouseInfo();
        JPanel tpanel = new JPanel();
        tpanel.add(new JLabel("hi"));
        popupAdd = factory.getPopup(null,tpanel , 100, 200);
        //popupButtonAdd.setName(l.getName());
        popupAdd.show();
      }
       
        void saySomething(String eventDescription, MouseEvent e) {

        }

    }
    class MyMouseListenerPlus implements MouseListener {
        public void mousePressed(MouseEvent e) {
     
        }

        public void mouseReleased(MouseEvent e) {

        }

        public void mouseEntered(MouseEvent e) {
            setCursor(Cursor.getPredefinedCursor(Cursor.HAND_CURSOR));
        }

        public void mouseExited(MouseEvent e) {
            setCursor(Cursor.getPredefinedCursor(Cursor.DEFAULT_CURSOR));
        }

        
        public void mouseClicked(MouseEvent e) {
            try {
            setCursor(Cursor.getPredefinedCursor(Cursor.DEFAULT_CURSOR));
            JLabel l = (JLabel)e.getComponent();
            final JPanel addPanel = new JPanel();
            addPanel.add(new JLabel("How many rows do you want to add?"));
            popupButtonAdd = new JButton("Add");
            addPanel.add(popupFieldAdd);
            Choice c = new Choice();
            for (int j = 1; j <15; j++)
                c.add(Integer.toString(j));
           // popupButtonAdd.setActionCommand("popupAdd");
           // popupButtonAdd.addActionListener(al);
            addPanel.add(c);
            popupButtonAdd.addActionListener( new ActionListener(){
                public void actionPerformed(ActionEvent e){
                    //adjustRows();
                   popupAdd.hide();
                }
           }
        );
   
        addPanel.add(popupButtonAdd);
        //addPanel.setBorder(BorderFactory.createLineBorder(Color.black));
        PopupFactory factory = PopupFactory.getSharedInstance();
        // MouseInfo mi = new MouseInfo();
        popupAdd = factory.getPopup(null,addPanel , 200, 200);
        //popupButtonAdd.setName(l.getName());
        popupAdd.show();
            }
            catch (IllegalArgumentException ie) {
                taskName.setText(ie.getMessage());
            }
      }
       
        void saySomething(String eventDescription, MouseEvent e) {

        }

    }
    class MyMouseListenerEdit implements MouseListener {
        public void mousePressed(MouseEvent e) {  
        }

        public void mouseReleased(MouseEvent e) {
        }

        public void mouseEntered(MouseEvent e) {
            setCursor(Cursor.getPredefinedCursor(Cursor.HAND_CURSOR));
        }

        public void mouseExited(MouseEvent e) {
            setCursor(Cursor.getPredefinedCursor(Cursor.DEFAULT_CURSOR));
        }

        
        public void mouseClicked(MouseEvent e) {
            JLabel l = (JLabel)e.getComponent();
            String val = l.getName();
            Integer id = new Integer(l.getName());
            int k = id.intValue();
            clearFields();
            editFlag = true;
            editID = k;
            Integer pt = new Integer(val.substring(1,3));
            int parent_task = pt.intValue();
            //child task
            String xx = new String(String.valueOf((char)val.charAt(0)));
            pt = new Integer(xx);
            int family_task = pt.intValue();
            Integer tt = new Integer(parent_task);
            task_node tmp;
            tmp = task_node_list[k];
            Integer t = new Integer((tmp.getPhase()+1));
            phase.setText(t.toString());
            taskName.setText(tmp.getName());
            if (tmp.getBank() > 1)
                taskName.disable();
            else
                taskName.enable();
            //taskName.setText(tt.toString());
            duration.select((tmp.getDuration()-1));
            for (int i = 0; i < tasktypelistSize; i++){
                panelTaskList[i].setVisible(true);
            }
            //family_task--;
            for (int i = 0; i < parentCategorySize; i++){
                if (parentCategoryListIndex[i] == parent_task)
                    parentCategory.select(i); 
            }
            family_task--;
            taskType.select(family_task);
            panelTaskList[family_task].setVisible(false);
            parent_task--;
            //parentCategory.select(parentCategoryListIndex[parent_task]);
            //find family tasks
            int tmpTask_id = k;
            tmpTask_id += 10000;

            while (tmpTask_id != k) {
                if (tmpTask_id >= 100000)
                    tmpTask_id -= 100000;
                String ss= taskName.getText();
                taskName.setText(ss);
                if (task_node_list[tmpTask_id] != null) {
                    int index = tmpTask_id / 10000;   
                    index--;
                    panelInfoList[index].setVisible(true);
                    if (task_node_list[tmpTask_id].getTag() != -1){
                        panelInfoTag[index].setVisible(true);
                        panelInfoNew[index].setVisible(false);
                        for (int q = 1; 1 < tasktypelistSize; q++){
                            Integer tmpN = new Integer(ButtonReminderList1[q].getName());
                            if (tmpN.intValue() == task_node_list[tmpTask_id].getTag())
                                ButtonReminderList1[q].setSelected(true);
                            tmpN = new Integer(ButtonReminderList2[q].getName());
                            if (tmpN.intValue() == task_node_list[tmpTask_id].getTag())
                                ButtonReminderList2[q].setSelected(true);
                            tmpN = new Integer(ButtonReminderList3[q].getName());
                            if (tmpN.intValue() == task_node_list[tmpTask_id].getTag())
                                ButtonReminderList3[q].setSelected(true);
                        }
                    } else{                               
                        panelInfoTag[index].setVisible(false);
                        panelInfoNew[index].setVisible(true);
                        name_list[index].setText(task_node_list[tmpTask_id].getName());
                        phase_list[index].setText((String.valueOf(task_node_list[tmpTask_id].getPhase()+1)));
                        duration_list[index].select((task_node_list[tmpTask_id].getDuration()-1));
                    }
                }     
                tmpTask_id += 10000;         
            }
            parentCategory.enable(false);
            taskType.enable(false);
        }
        public void clearFields() {
            editID = -1;
            //clear new box
            for (int i = 0; i < tasktypelistSize; i++)  {
                panelInfoList[i].setVisible(false);
                name_list[i].setText("");
                phase_list[i].setText("");
                duration_list[i].select(0);
                
            }
            //clear reminder box
            ButtonReminderList1[0].setSelected(true);
            ButtonReminderList2[0].setSelected(true);
            ButtonReminderList3[0].setSelected(true);
            phase.setText("");
            taskName.setText("");
            duration.select(0);
            taskName.enable();
            taskType.select(0);
            parentCategory.select(0);
            editFlag = false;
      }    
        
                
        void saySomething(String eventDescription, MouseEvent e) {
        }
    }
    
     class MyMouseListener implements MouseListener {
        public void mousePressed(MouseEvent e) {
        }

        public void mouseReleased(MouseEvent e) {
        }

        public void mouseEntered(MouseEvent e) {
            setCursor(Cursor.getPredefinedCursor(Cursor.HAND_CURSOR));
        }

        public void mouseExited(MouseEvent e) {
            setCursor(Cursor.getPredefinedCursor(Cursor.DEFAULT_CURSOR));
        }

        
        public void mouseClicked(MouseEvent e) {
            Label l = (Label)e.getComponent();

            if (l.getText() == "Create New Reminder") {
                l.setText("Tag To Existing Reminder");
                Integer t = new Integer(l.getName());
                panelInfoNew[t.intValue()].setVisible(true);
                panelInfoTag[t.intValue()].setVisible(false);
              
            }
            else{
                l.setText("Create New Reminder");
                Integer t = new Integer(l.getName());
                panelInfoNew[t.intValue()].setVisible(false);
                panelInfoTag[t.intValue()].setVisible(true);
            }
        }

        void saySomething(String eventDescription, MouseEvent e) {

        }

    }
    class MyChoiceListener implements ItemListener {
        public void itemStateChanged(ItemEvent event) {
            for (int i = 0; i < tasktypelistSize; i++){
                panelTaskList[i].setVisible(true);
            }
            for (int i = 0; i < tasktypelistSize; i++){
                if (event.getItem() == tasktypelist[i]) {
                    panelTaskList[i].setVisible(false);
                
                }
            }
        }
    }
    public void destroy(){
        try {
        stmt.close();
        }
            catch(Exception e)		
        {			
            field.setText("error: " + e.getMessage());		
        }  
        
    }   
}
