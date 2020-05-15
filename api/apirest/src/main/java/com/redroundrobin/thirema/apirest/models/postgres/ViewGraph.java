package com.redroundrobin.thirema.apirest.models.postgres;

import com.fasterxml.jackson.annotation.JsonIdentityInfo;
import com.fasterxml.jackson.annotation.JsonIdentityReference;
import com.fasterxml.jackson.annotation.JsonProperty;
import com.fasterxml.jackson.annotation.JsonValue;
import com.fasterxml.jackson.annotation.ObjectIdGenerators;
import java.io.Serializable;
import javax.persistence.Column;
import javax.persistence.GeneratedValue;
import javax.persistence.GenerationType;
import javax.persistence.Id;
import javax.persistence.JoinColumn;
import javax.persistence.ManyToOne;
import javax.persistence.SequenceGenerator;
import javax.persistence.Table;

@javax.persistence.Entity
@Table(name = "views_graphs")
public class ViewGraph implements Serializable {

  public enum Correlation {
    NULL, COVARIANCE, PEARSON, SPEARMAN;

    @JsonValue
    public int toValue() {
      return ordinal();
    }

    public static boolean isValid(int correlation) {
      for (int i = 0; i < Correlation.values().length; ++i) {
        if (correlation == i) {
          return true;
        }
      }
      return false;
    }
  }

  @Id
  @GeneratedValue(generator = "views_graphs_graph_id_seq", strategy = GenerationType.SEQUENCE)
  @SequenceGenerator(
      name = "views_graphs_graph_id_seq",
      sequenceName = "views_graphs_graph_id_seq",
      allocationSize = 25
  )
  @Column(name = "graph_id")
  private int graphId;
  private Correlation correlation;

  @ManyToOne
  @JoinColumn(name = "view_id")
  @JsonIdentityInfo(generator = ObjectIdGenerators.PropertyGenerator.class, property = "viewId")
  @JsonIdentityReference(alwaysAsId = true)
  private View view;

  @ManyToOne
  @JoinColumn(name = "sensor_1_id")
  @JsonIdentityInfo(generator = ObjectIdGenerators.PropertyGenerator.class, property = "sensorId")
  @JsonIdentityReference(alwaysAsId = true)
  private Sensor sensor1;

  @ManyToOne
  @JoinColumn(name = "sensor_2_id")
  @JsonIdentityInfo(generator = ObjectIdGenerators.PropertyGenerator.class, property = "sensorId")
  @JsonIdentityReference(alwaysAsId = true)
  private Sensor sensor2;

  public ViewGraph() {
    // default constructor
  }

  public ViewGraph(Correlation correlation) {
    this.correlation = correlation;
  }

  public ViewGraph(int graphId, Correlation correlation) {
    this.graphId = graphId;
    this.correlation = correlation;
  }

  @JsonProperty(value = "viewGraphId")
  public int getId() {
    return graphId;
  }

  public Correlation getCorrelation() {
    return correlation;
  }

  public void setCorrelation(Correlation correlation) {
    this.correlation = correlation;
  }

  public View getView() {
    return view;
  }

  public void setView(View view) {
    this.view = view;
  }

  public Sensor getSensor1() {
    return sensor1;
  }

  public void setSensor1(Sensor sensor1) {
    this.sensor1 = sensor1;
  }

  public Sensor getSensor2() {
    return sensor2;
  }

  public void setSensor2(Sensor sensor2) {
    this.sensor2 = sensor2;
  }
}
