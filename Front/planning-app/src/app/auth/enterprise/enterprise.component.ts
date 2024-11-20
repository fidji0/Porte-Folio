import { Component, OnInit } from '@angular/core';
import { AuthService } from 'src/app/services/auth.service';

@Component({
  selector: 'app-enterprise',
  templateUrl: './enterprise.component.html',
  styleUrls: ['./enterprise.component.scss'],
})
export class EnterpriseComponent  implements OnInit {

  constructor(public auth : AuthService) { }

  ngOnInit() {}

}
