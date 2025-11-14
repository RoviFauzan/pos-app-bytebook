import { createClient } from '@supabase/supabase-js'

// Supabase URL & anon key (jangan commit key sensitif selain anon)
const supabaseUrl = 'https://ecmfayciiwjrxcogeqlr.supabase.co'
const supabaseAnonKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImVjbWZheWNpaXdqcnhjb2dlcWxyIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NjMwNjMyMzIsImV4cCI6MjA3ODYzOTIzMn0.GjJzgnm6mh5fhnamxoQ291SX9Y3i0TGvZh3iGf-4i7E';

// Supabase client (native)
const client = createClient(supabaseUrl, supabaseAnonKey);

const supabase = {
  url: supabaseUrl,
  key: supabaseAnonKey,
  client,

  getHeaders() {
    return {
      'apikey': this.key,
      'Authorization': `Bearer ${this.key}`,
      'Content-Type': 'application/json'
    };
  },

  async fetch(endpoint, options = {}) {
    const url = `${this.url}/rest/v1/${endpoint}`;
    const defaultOptions = { headers: this.getHeaders() };

    const mergedOptions = {
      ...defaultOptions,
      ...options,
      headers: { ...defaultOptions.headers, ...(options.headers || {}) }
    };

    try {
      const response = await fetch(url, mergedOptions);
      if (!response.ok) {
        const errorText = await response.text();
        throw new Error(`Supabase API error ${response.status}: ${errorText}`);
      }
      return await response.json();
    } catch (error) {
      console.error(`Supabase fetch error (${endpoint}):`, error);
      throw error;
    }
  },

  async testConnection() {
    try {
      // Use a lightweight table (adjust if needed)
      const result = await this.fetch('barang?select=id_barang&limit=1');
      console.log('Supabase test OK', result);
      return true;
    } catch (e) {
      console.warn('Supabase test failed', e.message);
      return false;
    }
  }
};

const api = {
  client,
  async list(table, select='*') {
    const { data, error } = await client.from(table).select(select);
    if (error) throw error;
    return data || [];
  },
  async filtered(table, filterObj={}, select='*') {
    let q = client.from(table).select(select);
    Object.entries(filterObj).forEach(([k,v]) => q = q.eq(k,v));
    const { data, error } = await q;
    if (error) throw error;
    return data || [];
  },
  rupiah(n){ return 'Rp ' + (Number(n)||0).toLocaleString('id-ID'); }
};

window.supabase = supabase;
window.supabaseApi = api;
export default api;
