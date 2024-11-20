// src/app/services/auth.service.ts

import { Injectable } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Storage } from '@ionic/storage-angular';
import { BehaviorSubject, catchError, firstValueFrom, tap } from 'rxjs';
import { ConnexionUser, User } from '../class/user';
import { FormGroup } from '@angular/forms';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  public user: User = new User;
  public UserConnexionSubject = new BehaviorSubject<ConnexionUser[]>([]);
  public userConnexion: ConnexionUser = new ConnexionUser
  private TOKEN_KEY = 'User';
  private apiUrl = 'https://employe.liveproxim.fr'; // Remplacez par l'URL de votre API d'authentification
  authState = new BehaviorSubject(false);

  constructor(
    private http: HttpClient,
    private storage: Storage
  ) {
    console.log(this.userConnexion);

    this.storage.create();
    this.getUserConnexion();
    this.checkToken();
  }

  async checkToken() {
    const token = await this.storage.get(this.TOKEN_KEY);
    if (token) {
      this.authState.next(true);
    }
  }

  async login(credentials: FormGroup): Promise<User | boolean> {
    try {
      const response = await firstValueFrom(
        this.http.post<any>(`${this.apiUrl}/connexion`, credentials).pipe(
          tap(async (res) => {
            if (res && res.token) {
              this.user = res;

              await this.storage.set(this.TOKEN_KEY, JSON.stringify(res));
              this.authState.next(true);
            }
          }),
          catchError((error: HttpErrorResponse) => {

            throw error;
          })
        )
      );

      return response?.token;
    } catch (error) {

      return false;
    }
  }
  async logout() {
    this.user = null;
    await this.storage.remove(this.TOKEN_KEY);
    this.authState.next(false);
  }

  isAuthenticated(): boolean {
    return this.authState.value;
  }

  async getToken(): Promise<string | null> {
    let user = await this.storage.get(this.TOKEN_KEY);
    this.user = JSON.parse(user)
    return this.user?.token;
  }

  async getNotifId(): Promise<string | null> {
    let notif_id = await this.storage.get("notif_id");
    return notif_id;
  }
  async getUserConnexion(): Promise<void> {
    let userStock = await this.storage.get("user_connexion");

    
    if (userStock) {
      this.userConnexion = JSON.parse(userStock)
    }
    return;
  }
  async setToken(token: string) {
    await this.storage.set(this.TOKEN_KEY, token);
  }
  async setNotifId(notif_id: string) {
    await this.storage.set("notif_id", notif_id);
  }
  async setUserConnexion() {
    await this.storage.set("user_connexion", JSON.stringify(this.userConnexion));
  }
  async removeToken() {
    await this.storage.remove(this.TOKEN_KEY);
  }
}