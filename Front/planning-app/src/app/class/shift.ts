// src/app/models/schedule.model.ts
export class Shift {
    id: string;
    start_date: string;
    end_date: string;
    duration: string;
    type: string;
    lieu: string;
    objet: string;
    detail?: string;
    employe_id : string;
    name: string;
    surname : string;
    color : string;
    
  }
  
  export class Schedule {
    shifts: Shift[];
  }
  export class GroupedShifts {
    date : string;
    shifts: Shift[];
  }
  export class Absence {
    id: string;
    start_date: string;
    end_date: string;
    etat: string;
    type: string;
    created_at: string;
    objet: string;
    employe_id : string;
    result : boolean
   
  }
  export class Notif {
    id: string;
    title: string;
    message: string;
    employeView: string;   
    created_at : Date;
    result : boolean;
  }
  
  