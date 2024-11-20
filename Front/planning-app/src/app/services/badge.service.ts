import { Injectable } from '@angular/core';
import { Badge } from '@capawesome/capacitor-badge';

const get = async () => {
    const result = await Badge.get();
    return result.count;
  };
  
  const set = async (count: number) => {
    await Badge.set({ count });
  };
  
  const increase = async () => {
    await Badge.increase();
  };
  
  const decrease = async () => {
    await Badge.decrease();
  };
  
  const clear = async () => {
    await Badge.clear();
  };
  
  const isSupported = async () => {
    const result = await Badge.isSupported();
    return result.isSupported;
  };
  
  const checkPermissions = async () => {
    const result = await Badge.checkPermissions();
  };
  
  const requestPermissions = async () => {
    const result = await Badge.requestPermissions();
  };
@Injectable({
  providedIn: 'root'
})
export class BadgeService {

  constructor() { }

  setBadge(number : number){        
    set(number)
  }

  getBadge(){
    return get()
  }
  increaseBadge(){
    increase()
  }
  
  decreaseBadge(){
    decrease()
  }
  
  
  clearBadge(){
    clear()
  }
  
  isSupportedBadge(){
    return isSupported()
  }
  
  checkPermissionsBadge(){
    return checkPermissions()
  }
  
  requestPermissionsBadge(){
    requestPermissions()
  }
}
