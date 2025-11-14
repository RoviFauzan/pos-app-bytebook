import { createClient } from 'https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2.43.4/+esm';

// Replace with your actual project URL & anon key (anon key is safe for public use)
const supabaseUrl = 'https://ecmfayciiwjrxcogeqlr.supabase.co';
const supabaseAnonKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImVjbWZheWNpaXdqcnhjb2dlcWxyIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjMwNjMyMzIsImV4cCI6MjA3ODYzOTIzMn0.GjJzgnm6mh5fhnamxoQ291SX9Y3i0TGvZh3iGf-4i7E';

const client = createClient(supabaseUrl, supabaseAnonKey);

const supabase = {
  client,
  async fetchRest(endpoint, options = {}) {
    const url = `${supabaseUrl}/rest/v1/${endpoint}`;
    const defaultHeaders = {
      apikey: supabaseAnonKey,
      Authorization: `Bearer ${supabaseAnonKey}`,
      'Content-Type': 'application/json'
    };
    try {
      const res = await fetch(url, {
        ...options,
        headers: { ...defaultHeaders, ...(options.headers || {}) }
      });
      if (!res.ok) {
        const txt = await res.text();
        throw new Error(`Supabase REST error ${res.status}: ${txt}`);
      }
      return await res.json();
    } catch (e) {
      console.error('Supabase REST fetch error:', e);
      throw e;
    }
  },
  async testConnection() {
    try {
      await this.fetchRest('barang?select=id_barang&limit=1');
      console.log('Supabase REST test OK');
      return true;
    } catch (e) {
      console.warn('Supabase REST test failed', e.message);
      return false;
    }
  }
};

window.supabase = supabase;
export default supabase;
