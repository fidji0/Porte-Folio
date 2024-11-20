// src/app/components/event-detail-modal/event-detail-modal.component.ts

import { Component, Input } from '@angular/core';
import { ModalController } from '@ionic/angular';
import { Shift } from '../../class/shift';
import { AuthService } from 'src/app/services/auth.service';

@Component({
  selector: 'app-event-detail-modal',
  templateUrl: './event-detail-modal.component.html',
  styleUrls: ['./event-detail-modal.component.scss']
})
export class EventDetailModalComponent {
  @Input() shift: Shift;
  @Input() eventTypes: any;
  public EVENT_TYPES = {
    TRAVAIL: { name: 'Travail', color: '#4CAF50' },
    DEPLACEMENT: { name: 'Déplacement', color: '#2196F3' },
    CONGES: { name: 'Congés', color: '#FF9800' },
    MALADIE: { name: 'Maladie', color: '#F44336' },
    FORMATION: { name: 'Formation', color: '#9C27B0' },
    AUTRE: { name: 'Autre', color: '#607D8B' }
  };
  constructor(private modalController: ModalController , public auth : AuthService) {
    
  }

  dismiss() {
    this.modalController.dismiss();
  }

  getEventTypeColor(type: string): string {
    console.log(this.shift);
    
    return this.EVENT_TYPES[type]?.color || this.eventTypes.AUTRE.color;
  }
}