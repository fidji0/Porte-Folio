// src/app/services/date.service.ts

import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class DateService {
  
  private currentWeekSubject = new BehaviorSubject<Date>(new Date());
  currentWeek$ = this.currentWeekSubject.asObservable();

  constructor() {}

  getWeekDays(date: Date = new Date()): Date[] {
    const start = new Date(date);
    start.setDate(start.getDate() - start.getDay() + (start.getDay() === 0 ? -6 : 1)); // Commencer par lundi
    return Array(7).fill(0).map((_, i) => {
      const day = new Date(start);
      day.setDate(day.getDate() + i);
      return day;
    });
  }

  nextWeek() {
    const nextWeek = new Date(this.currentWeekSubject.value);
    nextWeek.setDate(nextWeek.getDate() + 7);
    this.currentWeekSubject.next(nextWeek);
  }

  previousWeek() {
    const prevWeek = new Date(this.currentWeekSubject.value);
    prevWeek.setDate(prevWeek.getDate() - 7);
    this.currentWeekSubject.next(prevWeek);
  }

  formatDate(date: Date): string {
    return date.toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long' });
  }
}