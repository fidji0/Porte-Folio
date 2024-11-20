// src/app/services/schedule.service.ts

import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { BehaviorSubject, from, Observable, switchMap, tap } from 'rxjs';
import { Absence, Notif, Schedule, Shift } from '../class/shift';
import { AuthService } from './auth.service';
import { FormGroup } from '@angular/forms';



@Injectable({
  providedIn: 'root'
})
export class ScheduleService {
  private apiUrl = 'https://eventplanning.liveproxim.fr';
  public allShiftsSubject = new BehaviorSubject<Shift[]>([]);
  public allNotifSubject = new BehaviorSubject<Notif[]>([]);
  public allAbsenceSubject = new BehaviorSubject<Absence[]>([]);
  private absUrl = "https://absence.liveproxim.fr";
  public unreadNotificationsCount: number = 0;

  public schedule : Shift[] 

  constructor(private http: HttpClient, private authService: AuthService
  ) {
    if (authService.isAuthenticated()) {
      this.getNotif().subscribe()
    this.getWeekSchedule().subscribe()
    this.updateUnreadNotificationsCount();
    }
    
  }
  
  getWeekSchedule(): Observable<Shift[]> {
    return from(this.authService.getToken()).pipe(
      switchMap(token => {
        const headers = new HttpHeaders({
          'Authorization': `Bearer ${token}`
        });

        return this.http.get<Shift[]>(`${this.apiUrl}/readEmploye`, { headers }).pipe(
          tap(res => this.allShiftsSubject.next(res)
          )
        );
      })
    );
  }
  setAbsence(form : FormGroup): Observable<Shift[]>{
    return from(this.authService.getToken()).pipe(
      switchMap(token => {
        const headers = new HttpHeaders({
          'Authorization': `Bearer ${token}`
        });
        
        return this.http.post<Shift[]>(`${this.absUrl}/user_create`, JSON.stringify(form.value) , { headers });
      })
    );
  }
  deleteAbsence(id : string|number): Observable<any>{
    return from(this.authService.getToken()).pipe(
      switchMap(token => {
        const headers = new HttpHeaders({
          'Authorization': `Bearer ${token}`
        });
        
        return this.http.delete<any>(`${this.absUrl}/deleteAbsenceEmploye?id=${id}`, { headers });
      })
    );
  }
  getAbsence(): Observable<Absence[]>{
    return from(this.authService.getToken()).pipe(
      switchMap(token => {
        const headers = new HttpHeaders({
          'Authorization': `Bearer ${token}`
        });
        
        return this.http.get<Absence[]>(`${this.absUrl}/readAllAbsence`, { headers }).pipe(
          tap(res => {            
            if(res[0].result == false){
              this.allAbsenceSubject.next([]);
              
            }else{              
              if (Array.isArray(res)) {
                this.allAbsenceSubject.next(res);              
              }
              
            }
            
          }
          )
        );
      })
    );
  }
  getNotif() : Observable<Notif[] | Notif>{
    return from(this.authService.getToken()).pipe(
      switchMap(token => {
        const headers = new HttpHeaders({
          'Authorization': `Bearer ${token}`
        });
        
        return this.http.get<Notif[] | Notif>(`${this.apiUrl}/readNotif`, { headers }).pipe(
          tap(res => {            
            if(res[0].result == false){
              this.allNotifSubject.next([]);
              
            }else{
              if (Array.isArray(res)) {
                this.allNotifSubject.next(res);
              this.updateUnreadNotificationsCount()
              
              }
              
            }
            
          }
          )
        );
      })
    );
  }

  updateNotifRead(): Observable<any>{
    return from(this.authService.getToken()).pipe(
      switchMap(token => {
        const headers = new HttpHeaders({
          'Authorization': `Bearer ${token}`
        });
        
        return this.http.post<any>(`${this.apiUrl}/updateNotif`, {} , { headers });
      })
    );
  }
  
  getVersion(): Observable<any>{
    return this.http.get<any>(`${this.apiUrl}/version`)
  }
  getAllNotifications() {
    return this.allNotifSubject.asObservable();
  }
  getAllAbsence() {
    return this.allAbsenceSubject.asObservable();
  }
  async updateUnreadNotificationsCount() {
    this.allNotifSubject?.subscribe(res => {
      this.unreadNotificationsCount = res.filter(notif => notif.employeView == '0').length;
      


    })
  }
}