import { Component, OnInit } from '@angular/core';
import { AuthService } from 'src/app/services/auth.service';

@Component({
  selector: 'app-perso',
  templateUrl: './perso.component.html',
  styleUrls: ['./perso.component.scss'],
})
export class PersoComponent  implements OnInit {

  constructor(public auth : AuthService) { }

  ngOnInit() {}

}
